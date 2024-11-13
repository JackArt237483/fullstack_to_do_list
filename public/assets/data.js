    document.addEventListener('DOMContentLoaded', function (){

        const loginForm = document.getElementById('loginForm')
        const registerForm = document.getElementById('registerForm')
        const registerMessage = document.getElementById('registerMessage')
        const loginMessage = document.getElementById('loginMessage')
        const taskButton = document.getElementById('taskButton')
        const userForm = document.getElementById('user-form')

        async function postData (url = "",data = {}) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json'
                },
                body: JSON.stringify(data)
            })

            return response.json()
        }
        //запрос вход регисртрации
        if(registerForm){
            // форма регистрации запрос
            registerForm.addEventListener('submit',function(e){
                e.preventDefault();
                const username = document.getElementById('username').value
                const email =document.getElementById('email').value
                const phone = document.getElementById('phone').value
                const password = document.getElementById('password').value

                postData('http://localhost/sites/Block/backend/data.php',{
                    action: 'register',
                    username: username,
                    email: email,
                    phone: phone,
                    password: password,
                })
                    .then(data=> {
                        if(data.success){
                            registerMessage.textContent = 'Registration successful'
                            setTimeout(()=>{
                                window.location.href = 'login.html'
                            },2000)
                        } else {
                            registerMessage.textContent = `It,s mistake of registration ${data.error}`
                        }
                    })
                    .catch(error => {
                        console.error('Error man',error)
                    })
            })
        }
        //запрос вход логина
        if (loginForm){
            // форма логина запрос
            loginForm.addEventListener('submit', function(e){
                e.preventDefault()
                const username = document.getElementById('login_username').value
                const password = document.getElementById('login_password').value

                postData('http://localhost/sites/Block/backend/data.php',{
                    action: 'login',
                    username: username,
                    password: password
                })
                    .then(data => {
                        if(data.success){
                            loginMessage.textContent = `Login user is here ${data.user}`
                            setTimeout(()=>{
                                window.location.href = 'index.html'
                            },1000)
                        } else {
                            loginMessage.textContent = 'All info we had an error here' + data.error
                        }
                    })
            })
        }
        // переход на другую страницу
        taskButton.addEventListener('click',function (){
            window.location.href = 'index.html';
        })
        // переход на другую сторону
        // Запрос на изменение данных пользователя
        if(userForm){
            userForm.addEventListener('submit',function (e){
                e.preventDefault();

                const data = {
                    action: 'update',
                    username: document.getElementById('username').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    password: document.getElementById('password').value
                };

                // Вместо POST используем PUT для обновления данных
                fetch('http://localhost/sites/Block/backend/data.php?action=update', {
                    method: 'PUT',  // Используем PUT для обновления
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Данные успешно обновлены');
                        } else {
                            alert(data.message || 'Ошибка при обновлении данных');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }

        // запрос нв изменение данных и получение
        fetch('http://localhost/sites/Block/backend/data.php?action=update')
            .then(response => response.json())
            .then(data => {
                if(data.success){
                        document.getElementById('username').value = data.data.username
                        document.getElementById('email').value = data.data.email
                        document.getElementById('phone').value = data.data.phone
                        document.getElementById('password').value = data.data.password
                } else{
                    console.error(data.error);
                    alert("Ошибка: " + data.error);
                }
            })
            .catch(error => console.error('error' ,error))


    })

