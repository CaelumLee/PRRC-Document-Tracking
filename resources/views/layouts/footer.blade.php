@stack('scripts')
<!-- Compiled and minified MaterializeCSS JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{asset('js/app.js')}}"></script>
<script>
    $(document).ready(function(){
        // $('.sidenav').sidenav();
        $('.alert_close').click(function(){
            $( ".message" ).fadeOut( "slow", function() {
            });
        });
        $('.dropdown-trigger').dropdown({
            coverTrigger : false,
            constrainWidth : false
        });
        $('.dropdown-trigger').click(function(){
            alert('Pindot');
        })
    })
</script>
    </body>
</html>