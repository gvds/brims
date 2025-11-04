<?php

namespace App\Imports;

use App\Enums\SubjectStatus;
use App\Models\Arm;
use App\Models\Site;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

// class SubjectImporter implements ToCollection, WithHeadingRow
class SubjectImporter implements ToModel, WithHeadingRow, WithValidation
{
    public function __construct(public int $project_id)
    {
        //
    }

    public function model(array $row)
    {
        return new Subject([
            'subjectID' => $row['subjectid'] ?? null,
            'site_id' => Site::where('name', $row['site'])->where('project_id', $this->project_id)->first()?->id,
            'user_id' => User::where('username', $row['user'])->first()?->id,
            'firstname' => $row['firstname'] ?? null,
            'lastname' => $row['lastname'] ?? null,
            'address' => $row['address'] ?? null,
            'enrolDate' => $row['enroldate'] ?? null,
            'arm_id' => Arm::where('name', $row['arm'])->where('project_id', $this->project_id)->first()?->id,
            'armBaselineDate' => $row['armbaselinedate'] ?? null,
            'status' => isset($row['status']) ? SubjectStatus::from($row['status']) : null,
            'project_id' => $this->project_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'subjectid' => [
                'required',
                'unique:subjects,subjectID',
                // 'unique:subjects,subjectID,NULL,id,project_id,' . $this->project_id
            ],
            'site_id' => [
                'required',
                Rule::exists('sites', 'id')->where('project_id', $this->project_id)
            ],

        ];
    }
}
