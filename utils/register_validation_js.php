<script>
$(document).ready(function() {

    $('#register-submit').click(function() {
        var name = $('#name').val();
        var email = $('#email').val();
        var pasword = $('#pasword').val();
        var paswordr = $('#paswordr').val();
        
        if (!name) {
            alert('Imię jest polem wymaganym.');
            return false;
        }
        
        if (!email) {
            alert('Email jest polem wymaganym.');
            return false;
        }
        
        if (!validateEmail(email)) {
            alert('Email ma niepoprawną formę.');
            return false;
        }
        
        if (!pasword) {
            alert('Hasło jest polem wymaganym.');
            return false;
        }
        
        if (!paswordr) {
            alert('Proszę powtórzyć hasło.');
            return false;
        }
        
        if (pasword!=paswordr) {
            alert('Hasła muszą się zgadzać');
            return false;
        }
        
        $('#register-form').submit()        
    });
});
</script>
