<script>
    function keyUpInEditor() {
        $("#<?=$lcEditorName?>").val($("#txtEditor").Editor("getText"));
        $("#updateEditor").val(1);
    }
</script>


<script src="/line-control/editor.js"></script>
<link href="/line-control/editor.css" type="text/css" rel="stylesheet"/>

<div class="text-left">
        <textarea name="txtEditor" id="txtEditor"></textarea>
</div> 

<input type="hidden" id="<?=$lcEditorName?>" name="<?=$lcEditorName?>" value="" />
<input type="hidden" id="updateEditor" name="updateEditor" value="" />

<script>
$("#txtEditor").Editor();
$("#txtEditor").Editor("setText", '<?=$lcEditorContent?>')

//$lcEditorContent
/*
$(document).ready(function() {
        $("#save").click(function() {
                $("#txtEditor").Editor("getText");
                alert($("#txtEditor").Editor("getText"));
                $("#txtEditorBuffer").val($("#txtEditor").Editor("getText"));
        })  
        
        $("#txtEditor").Editor('createMenuItem', { "text": "WordCount",
                "icon":"icon icon-glass",
                "tooltip": "WordCount",
                "commandname": null,
                "custom":function(button, parameters){
                                alert("I am here");
                        },
                "params": {'option':"value"}
        });
});
*/
</script>

