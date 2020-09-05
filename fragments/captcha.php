        <link rel="stylesheet" type="text/css"  href="/css/captcha.css" />
        <script src="/js/captcha.js"></script>
            
        <div id="captcha_modal" class="captcha">
                <div id="captcha_content">
                        <div id="captcha_info" class="">Nie som robot ...</div>
                        <div id="captcha_confirm" class="">
                              <div id="captcha_key"  data-toggle="tooltip"  title="Potvrďte že nie ste robot !" class="">
                                    <i class="fas fa-sign-in-alt"></i>
                              </div>
                        </div>
                </div>
        </div> 
        <script>$(document).ready(function(){$('[data-toggle="tooltip"]').tooltip();});</script>