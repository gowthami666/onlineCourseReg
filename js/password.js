

var pass = angular.element('password');
var meter = angulr.element('password-strength-meter');


pass.addEventListener('input', function()
{
    var val = pass.value;
    var result = zxcvbn(val);
    
    // Update the password strength meter
    meter.value = result.score;
   
    
});

