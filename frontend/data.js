    document.addEventListener('DOMContentLoaded', function (){

        const loginForm = document.getElementById('loginForm')
        const registerForm = document.getElementById('registerForm')
        const registerMessage = document.getElementById('registerMessage')
        const loginMessage = document.getElementById('loginMessage')

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


    })

