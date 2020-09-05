
<div class="container text-center">
        <iframe id="exhibition" style="width:100%;height:400px;" src="" frameborder="0" allowtransparency="true" allowfullscreen></iframe>
</div>

<?
        $oSource = $db->get("VIDEO_PATH","sourceInFrame", "src_path", "src_id=1", true, __FILE__, __LINE__, true);
?>
<input type="hidden" id="object" name="object" value="<?=unc($oSource["src_path"])?>">

        <br />

<script>
$(document).ready(function() {$('#exhibition').attr('src', dec($('#object').val()));})
</script>