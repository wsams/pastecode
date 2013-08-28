$(document).ready( function() {
    
    var location = window.location.pathname;
    
    // Set up the editor
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/ambiance");
    editor.getSession().setFoldStyle("markbegin");
    
    // This will be the index page, also where pastes are posted from
    if (location == "/") {
        // When the language is changed, we need to change the language of the editor
        $("#language").change(function() {
            var mode = $("#language").val();
            editor.getSession().setMode("ace/mode/"+mode);
        });
        
        // On submit, we need to put the contents of the editor into our form element
        $("#createPaste").submit(function () {
            $("#contents").val(editor.getSession().getValue());
        });
        
    } else {
        // This should be the view paste page
        var mode = $("#language").attr("lang");
        editor.getSession().setMode("ace/mode/"+mode);
        editor.setReadOnly(true);
    }
    
});