document.querySelector("form").onsubmit = function(event) {
    let fname = document.querySelector("input[name='fname']").value;
    let lname = document.querySelector("input[name='lname']").value;
    let email = document.querySelector("input[name='email']").value;
    let password = document.querySelector("input[name='password']").value;
    let cpassword = document.querySelector("input[name='cpassword']").value;
    let patient = document.querySelector("input[name='patient']").checked;
    let doctor = document.querySelector("input[name='doctor']").checked;

    // Check if any of the required fields are empty (except username)
    if (!fname || !lname || !email || !password || !cpassword) {
        alert("All fields must be filled out.");
        event.preventDefault();
        return false;
    }

    // Check if passwords match
    if (password !== cpassword) {
        alert("Passwords do not match.");
        event.preventDefault();
        return false;
    }

    // Email validation regex (simple)
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
        return false;
    }

    // Password validation regex
    let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
    if (!passwordRegex.test(password)) {
        alert("Password must have at least one uppercase letter, one lowercase letter, one number, one special character, and be more than 6 characters.");
        event.preventDefault();
        return false;
    }

    // Check if at least one user type (Patient or Doctor) is selected
    if (!patient && !doctor) {
        alert("Please select if you are a Patient or a Doctor.");
        event.preventDefault();
        return false;
    }

    return true;
};




