<!-- Bootstrap core JavaScript-->
<script src="{{asset('sbadmin/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!--Popper-->
<script src="{{asset('sbadmin/vendor/popper/popper.min.js')}}"></script>

<!-- Core plugin JavaScript-->
<script src="{{asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('sbadmin/js/sb-admin-2.js')}}"></script>

<!--Select2-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--Scripts propios-->
<script src="{{asset('js/scripts.js')}}"></script>

<!--Variable con configuraciones varias para ser usadas en los archivos js-->
<script>
    var config = {
            routes: {
                verificarDni: "{{route('verificarCampo', 'dni')}}",
                verificarLu: "{{route('verificarCampo', 'lu')}}",
                verificarEmail: "{{route('verificarCampo', 'email')}}",
                verificarPassword: "{{route('verificarPassword')}}"
            }
        }
</script>