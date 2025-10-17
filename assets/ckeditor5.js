// assets/ckeditor5.js
import {
    Autoformat,
    Bold,
    ClassicEditor,
    Essentials,
    Italic,
    Link,
    Paragraph,
} from 'ckeditor5';
// Si vous devez importer des traductions, ici les traductions en français
import coreTranslations from 'ckeditor5/translations/fr.js';
import 'ckeditor5/dist/ckeditor5.min.css';

export default class EnhancedEditor extends ClassicEditor {}

EnhancedEditor.builtinPlugins = [Autoformat, Bold, Essentials, Italic, Link, Paragraph];

EnhancedEditor.defaultConfig = {
    licenseKey: 'GPL',
    toolbar: [
        'bold',
        'italic',
        '|',
        'link',
        '|',
        'undo',
        'redo',
    ],
    // Vous pouvez supprimer la ligne suivante si vous n'avez pas besoin de charger des traductions
    translations: [coreTranslations],
};
