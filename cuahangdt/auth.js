document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.getElementById("register-form");
    const loginForm = document.getElementById("login-form");

    // ===================== ĐĂNG KÝ =====================
    if (registerForm) {
        registerForm.addEventListener("submit", async function (e) {
            e.preventDefault();

            const usernameInput = document.getElementById("register-username");
            const emailInput = document.getElementById("register-email");
            const passwordInput = document.getElementById("register-password");
            const messageDiv = document.getElementById("register-message");

            const username = usernameInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();

            messageDiv.innerText = ""; // Clear old message
            [usernameInput, emailInput, passwordInput].forEach(input => input.style.border = "");

            let hasError = false;

            if (!username) {
                usernameInput.style.border = "2px solid red";
                hasError = true;
            }

            if (!email) {
                emailInput.style.border = "2px solid red";
                hasError = true;
            }

            if (!password) {
                passwordInput.style.border = "2px solid red";
                hasError = true;
            }

            if (hasError) {
                messageDiv.innerText = "Vui lòng điền đầy đủ thông tin!";
                messageDiv.style.color = "red";
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                emailInput.style.border = "2px solid red";
                messageDiv.innerText = "Email không hợp lệ!";
                messageDiv.style.color = "red";
                return;
            }

            if (password.length < 6) {
                passwordInput.style.border = "2px solid red";
                messageDiv.innerText = "Mật khẩu phải có ít nhất 6 ký tự!";
                messageDiv.style.color = "red";
                return;
            }

            const formData = new FormData();
            formData.append("username", username);
            formData.append("email", email);
            formData.append("password", password);

            try {
                const response = await fetch("register.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();
                console.log(data);

                if (data.status === "success") {
                    // Lưu username để điền sẵn vào form login (tùy chọn)
                    localStorage.setItem("registeredUser", JSON.stringify({
                        username: username,
                        email: email
                    }));

                    messageDiv.innerText = "Đăng ký thành công! Đang chuyển đến trang đăng nhập...";
                    messageDiv.style.color = "green";
                    registerForm.reset();

                    setTimeout(() => {
                        window.location.href = "login.html";
                    }, 2000);
                } else {
                    messageDiv.innerText = data.message;
                    messageDiv.style.color = "red";
                }
            } catch (error) {
                console.error("Lỗi kết nối:", error);
                messageDiv.innerText = "Lỗi đăng ký! Hãy thử lại.";
                messageDiv.style.color = "red";
            }
        });
    }

    // ===================== ĐĂNG NHẬP =====================
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const usernameInput = document.getElementById("login-username");
            const passwordInput = document.getElementById("login-password");
            const messageDiv = document.getElementById("login-message");

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            messageDiv.innerText = "";
            [usernameInput, passwordInput].forEach(input => input.style.border = "");

            if (!username || !password) {
                if (!username) usernameInput.style.border = "2px solid red";
                if (!password) passwordInput.style.border = "2px solid red";

                messageDiv.innerText = "Vui lòng nhập tên đăng nhập và mật khẩu!";
                messageDiv.style.color = "red";
                return;
            }

            // Giả lập xác thực (nếu dùng PHP, bạn cần fetch về giống như bên register)
            // Ở đây ví dụ đơn giản:
            const registeredData = JSON.parse(localStorage.getItem("registeredUser"));
            if (registeredData && username === registeredData.username) {
                // Đăng nhập thành công
                localStorage.setItem("loggedInUser", username);
                messageDiv.innerText = "Đăng nhập thành công!";
                messageDiv.style.color = "green";

                setTimeout(() => {
                    window.location.href = "home.html"; // chuyển sang trang chính
                }, 1500);
            } else {
                messageDiv.innerText = "Sai tên đăng nhập hoặc mật khẩu!";
                messageDiv.style.color = "red";
            }
        });
    }
});
