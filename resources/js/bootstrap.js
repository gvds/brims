import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import * as tus from 'tus-js-client';
window.tus = tus;

// import Uppy from '@uppy/core';
// import Dashboard from '@uppy/dashboard';
// import Dropzone from '@uppy/core';
// // import DragDrop from '@uppy/drag-drop';
// // import StatusBar from '@uppy/status-bar';
// import Tus from '@uppy/tus';

// import '@uppy/core/css/style.min.css';
// import '@uppy/dashboard/css/style.min.css';
// // import '@uppy/drag-drop/css/style.min.css';
// // import '@uppy/status-bar/css/style.min.css';

// window.Uppy = Uppy
// window.Dashboard = Dashboard
// window.Dropzone = Dropzone
// // window.DragDrop = DragDrop
// // window.StatusBar = StatusBar
// window.Tus = Tus
