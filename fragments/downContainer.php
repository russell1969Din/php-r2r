<style>
#downContainer {
        width:100%;
        height:35px;
        border:solid 0px red;
        text-align:center;        
        padding-top:5px;
        background-color:#8E8E8E;
        color:#CACACA;

        /*
        position: absolute;
        */
}
</style>

<button onclick="topFunction()" id="myBtn" title="Do hornej časti"><i class="far fa-arrow-alt-circle-up"></i></button>
<br /><br /><br />
<div id="downContainer" class="container-fluid fixed-bottom">
        &copy&nbsp;PROPERTY spol. s r. o., autor: Land Solution spol. s r. o. 2020, všetky práva sú vyhradené&nbsp;! 
</div>
<input type="hidden" id="downDifference" name="downDifference" value="35" />


<script>
        //Get the button
        var mybutton = document.getElementById("myBtn");
        
        
        setDownLine();

        window.onscroll = function() {setDownLine(); scrollFunction()}
        function setDownLine() {
                var diff = $("#downDifference").val();
                //$("#downContainer").css({top: ((document.documentElement.scrollTop + $(window).height())-35) + 'px'});
        }
        
        function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                        mybutton.style.display = "block";
                } else {
                        mybutton.style.display = "none";
                }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
        }
        
        var copy = '&copy&nbsp;PROPERTY spol. s r. o., autor: Land Solution spol. s r. o. 2020, všetky práva sú vyhradené&nbsp;!';
        
        if($(window).width()<1024) {var copy = '&copy&nbsp;PROPERTY spol. s r. o., autor: Land Solution spol. s r. o. 2020';}
        
        if($(window).width()<640) {var copy = '&copy Autor: Land Solution s r. o. 2020';}
        
        if($(window).width()<600) {var copy = '&copy Autor: Land Solution s r.o.';}
        
        //$("#downContainer").css({height: '35px'})
        //$("#downContainer").css({top: ((document.documentElement.scrollTop + $(window).height())-35) + 'px'});
        $("#downContainer").html(copy);
</script>