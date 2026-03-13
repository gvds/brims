<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class VirtualUnit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * @return BelongsTo <Project, VirtualUnit>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo <Specimentype, VirtualUnit>
     */
    public function specimentype(): BelongsTo
    {
        return $this->belongsTo(Specimentype::class);
    }

    /**
     * @return BelongsTo <PhysicalUnit, VirtualUnit>
     */
    public function physicalUnit(): BelongsTo
    {
        return $this->belongsTo(PhysicalUnit::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function freeLocations(): HasMany
    {
        return $this->locations()
            ->where('used', false)
            ->orderBy('id');
    }

    public function usedlocations(): HasMany
    {
        return $this->locations()
            ->where('used', 1)
            ->orderBy('id');
    }

    public function gaplocations(): HasMany
    {
        return $this->locations()
            ->where('used', 0)
            ->where('id', '<', ($this->lastused()?->id ?? 0))
            ->orderBy('id');
        // ->get();
    }

    public function lastused(): ?Location
    {
        return $this->locations()
            ->where('used', 1)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function consolidate()
    {
        if (($gaplocations = $this->gaplocations()->get())->count() === 0) {
            throw new Exception('There are no gaps in this virtual unit');
        }
        $usedlocations = $this->usedlocations()->get();
        $relocations = [];
        while ($gaplocations->count() > 0 && ($usedlocations->last()?->id ?? null) > ($gaplocations->last()?->id ?? null)) {
            $gaplocation = $gaplocations->shift();
            $usedlocation = $usedlocations->pop();
            $relocations[$gaplocation->id] = clone $usedlocation;
            $gaplocation->update([
                'used' => 1,
                'virgin' => 1,
                'barcode' => $relocations[$gaplocation->id]->barcode,
                'container_id' => $relocations[$gaplocation->id]->container_id,
            ]);
            $usedlocation->update([
                'used' => 0,
                'virgin' => 1,
                'barcode' => null,
                'container_id' => null,
            ]);
        }
        $containers = Specimen::whereIn('location_id', Arr::pluck($relocations, 'id'))
            ->where('status', 'inStorage')
            ->get()->keyBy('location_id');
        $consolidation = StorageConsolidation::create([
            'user_id' => Auth::user()->id,
            'virtual_unit_id' => $this->id,
        ]);
        foreach ($relocations as $location_id => $relocated) {
            if (! isset($containers[$relocated->id])) {
                throw new Exception('Container with barcode: '.$relocated->barcode.' in rack: '.$relocated->rack.', box: '.$relocated->box.', position: '.$relocated->position.' not found for relocation');
            }
            $containers[$relocated->id]->location_id = $location_id;
            $consolidation->addRelocation($relocated->barcode, $relocated->id, $location_id);
        }
        foreach ($containers as $key => $container) {
            $container->update();
        }
    }

    public function free_extents()
    {
        if ($this->rack_extent === 'Full') {
            return $this->endRack - ($this->lastused()?->rack ?? $this->startRack);
        } else {
            return ord($this->endBox) - ord(($this->lastused()?->box ?? $this->startBox));
        }
    }

    public function removeRacks(int $shrinkby)
    {
        $this->locations()
            ->whereIn('rack', range($this->endRack - $shrinkby + 1, $this->endRack))
            ->delete();
        $this->endRack = $this->endRack - $shrinkby;
        if ($this->startRack === $this->endRack) {
            $this->rack_extent = 'Partial';
        }
        $this->save();
    }

    public function removeBoxes(int $shrinkby)
    {
        if ($this->physicalUnit->unitDefinition->boxDesignation === 'Numeric') {
            $this->locations()
                ->whereIn('box', range($this->endBox - $shrinkby + 1, $this->endBox))
                ->delete();
            $this->endBox = $this->endBox - $shrinkby;
        } else {
            $this->locations()
                ->whereIn('box', range(chr(ord($this->endBox) - $shrinkby + 1), $this->endBox))
                ->delete();
            $this->endBox = chr(ord($this->endBox) - $shrinkby);
        }
        $this->save();
    }
}
