<?php
    namespace Controllers;

    use Classes\Email;
    use MVC\Router;
    use Model\Usuario;

    class LoginController {
        public static function login(Router $router) {

            $alertas = [];

            if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {

                $auth = new Usuario($_POST);

                $alertas = $auth->validarLogin();
                
                if( empty($alertas) ) {
                    // Comprobar que exista el usuario
                    $usuario = $auth->where('email', $auth->email);

                    if( $usuario ) {
                        $resultado = $usuario->comprobarPasswordAndVerificado($auth->password);

                        if( $resultado ) {
                            if( !isset($_SESSION) ){
                                session_start();
                            } 
                            
                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;

                            if( $usuario->admin === '1' ) {
                                $_SESSION['admin'] = $usuario->admin ?? null;
                                Header('Location: /admin');
                            } else {
                                Header('Location: /cita');
                            }

                            debuguear($_SESSION);  
                        }
                    } else {
                        Usuario::setAlerta('error','No existe el usuario');
                    }
                }
            }
            
            $alertas = Usuario::getAlertas();

            $router->render('auth/login',[
                'alertas' => $alertas
            ]);
        }

        public static function logout() {
            session_start();

            $_SESSION = [];

            header('Location: /');
        }

        public static function olvide(Router $router) {
            $alertas = [];

            if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $auth = new Usuario($_POST);

                $alertas = $auth->validarEmail();

                if( empty($alertas) ) {
                    $usuario = $auth->where('email', $auth->email);
                    
                    if( $usuario && $usuario->confirmado === '1' ) {
                        $usuario->crearToken();
                        $usuario->guardar();

                        // Enviar email
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                        $email->enviarInstrucciones();

                        Usuario::setAlerta('exito','Se mandó las instrucciones a tu correo');
                        
                    } else {
                        Usuario::setAlerta('error','El usuario no existe o no está confirmado');
                    }
                }
            }

            $alertas = Usuario::getAlertas();
            $router->render('auth/olvide',[
                'alertas' => $alertas
            ]);
        }

        public static function recuperar(Router $router) {

            $alertas = [];
            $token = s($_GET['token']);
            $error = false;

            $usuario = Usuario::where('token', $token);
        
            if( empty($usuario) ) {
                Usuario::setAlerta('error','Token invalido');
                $error = true;
            }
            
            if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $password = new Usuario($_POST);
                $alertas = $password->validarPassword();

                if( empty($alertas) ) {
                    $usuario->password = null;
                    $usuario->token = null;

                    $usuario->password = $password->password;

                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if( $resultado ) {
                        Header('Location: /');
                    }
                }
            }

            $alertas = Usuario::getAlertas();
            $router->render('auth/recuperar', [
                'alertas' => $alertas,
                'error' => $error
            ]);
        }

        public static function crear(Router $router) {
            // Alertas vacías
            $alertas = [];
            // Instanciando un nuevo objeto de usuario
            $usuario = new Usuario;

            if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
                $usuario->sincronizar($_POST);
                $alertas = $usuario->validarNuevaCuenta();

                if( empty($alertas) ) {
                    // Verificar si el usuario existe
                    $resultado = $usuario->existeUsuario();

                    if( $resultado->num_rows ) {
                        $alertas = Usuario::getAlertas();
                    } else {
                        $usuario->hashPassword();
                        $usuario->crearToken();
                        $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                        $email->enviarConfirmacion();

                        // Crea el usuario
                        $resultado = $usuario->crear();
                        
                        if( $resultado['resultado'] ) {
                            Header('Location: /mensaje');
                        }
                    }
                }
            }

            $router->render('auth/crear-cuenta', [
                'usuario' => $usuario,
                'alertas' => $alertas
            ]);
        }

        public static function mensaje(Router $router) {
            $router->render('auth/mensaje');
        }

        public static function confirmar(Router $router) {
            $alertas = [];

            // Obteniendo el token de la url
            $token = s($_GET['token']);

            $usuario = Usuario::where('token', $token);

            if( empty($usuario) ) {
                Usuario::setAlerta('error','El usuario no existe');
            } else {
                $usuario->confirmado = "1";
                $usuario->token = '';
                $usuario->guardar();
                Usuario::setAlerta('exito', 'Usuario confirmado correctamente');
            }

            $alertas = Usuario::getAlertas();
            $router->render('auth/confirmar-cuenta',[
                'alertas' => $alertas
            ]);
        }
    }

?>