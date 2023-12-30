import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginImageEdit from 'filepond-plugin-image-edit';
import FileRobotImageEditor from 'filerobot-image-editor';
import FilePondPluginFileEncode from 'filepond-plugin-file-encode';

window.FileRobotImageEditor = FileRobotImageEditor;


window.FilePond = FilePond
window.FilePond.registerPlugin(FilePondPluginFileEncode);
window.FilePond.registerPlugin(FilePondPluginImagePreview);
window.FilePond.registerPlugin(FilePondPluginImageEdit);

import '../../../../../../node_modules/filepond/dist/filepond.min.css';
import '../../../../../../node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import '../../../../../../node_modules/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css';
import '../../css/tablar-kit.css';

