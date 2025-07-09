tinymce.init({
    selector: '#noticia',
    plugins: 'image link media lists table code',
    toolbar: 'undo redo | styleselect | bold italic underline | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor backcolor | code',
    menubar: false,
    height: 400,
    automatic_uploads: true,
    images_upload_url: 'upload_imagem.php',
    images_upload_handler: function (blobInfo, success, failure) {
        success("data:" + blobInfo.blob().type + ";base64," + blobInfo.base64());
    }
});
// Garante que o conteúdo do TinyMCE seja enviado no submit
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('form').addEventListener('submit', function () {
        tinymce.triggerSave();
    });
});