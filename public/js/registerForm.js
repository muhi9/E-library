// Check if passwords match
document.addEventListener('DOMContentLoaded', function () {
   function checkPasswordsMatch() {
      let password = document.getElementById('registration_form_plainPassword').value;
      let repeatPassword = document.getElementById('registration_form_repeatPassword').value;

      if (password !== repeatPassword) {
         document.getElementById('passwordMatchError').style.display = 'block';
      } else {
         document.getElementById('passwordMatchError').style.display = 'none';
      }
   }
   // Check passwords match when the repeat password input box is updated
   document.getElementById('registration_form_repeatPassword').addEventListener('input', checkPasswordsMatch);
   
   // validate email
   
   function validateEmail() {
      let input = document.getElementById('registration_form_email')
      let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
      
      if (!input.value.match(validRegex)) {
         console.log(input.value.match(validRegex));
         document.getElementById('emailErr').style.display = 'block';
      } else {
         document.getElementById('emailErr').style.display = 'none';
      }
      
   }
   document.getElementById('registration_form_email').addEventListener('change', validateEmail);

   // validate phone number
   function validatePhone() {
      let input = document.getElementById('registration_form_phone')
      let regex = /^[0-9]*$/;
      
      if (!input.value.match(regex)) {
         document.getElementById('phoneErr').style.display = 'block';
      } else {
         document.getElementById('phoneErr').style.display = 'none';
      }
      
   }
   document.getElementById('registration_form_phone').addEventListener('change', validatePhone);
});
