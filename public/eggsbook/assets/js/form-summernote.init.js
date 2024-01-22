$(document).ready(function(){
	$('.lfm').filemanager('image');
    // Define function to open filemanager window
    var lfm = function(options, cb) {
        var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = cb;
    };
    // Define LFM summernote button
    var LFMButton = function(context) {
        var ui = $.summernote.ui;
        var button = ui.button({
            contents: '<i class="note-icon-picture"></i> ',
            tooltip: 'Insert image with filemanager',
            click: function() {
                lfm({type: 'image', prefix: '/laravel-filemanager'}, function(url, path) {
                    context.invoke('insertImage', url);
                });
            }
        });
        return button.render();
    };
    // Initialize summernote with LFM button in the popover button group
    // Please note that you can add this button to any other button group you'd like
    $('.summernote-editor').summernote({
        height: 300,                 // set editor height
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        focus: true,                 // set focus to editable area after initializing summernote
        toolbar: [
            ['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			['color', ['color']],
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
            ['height', ['height']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
            ['popovers', ['lfm']],
			['insert', ['link', 'video']],
			['view', ['fullscreen', 'codeview', 'help']]
        ],
        buttons: {
         lfm: LFMButton
        }
    })
});