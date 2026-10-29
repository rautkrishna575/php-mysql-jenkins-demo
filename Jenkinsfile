pipeline {
    agent any

    triggers {
        pollSCM('H/5 * * * *')
    }

    stages {
        stage('Check Docker') {
            steps {
                sh 'docker version'
                sh 'docker compose version'
            }
        }

        stage('Validate Compose') {
            steps {
                sh 'docker compose config --quiet'
            }
        }

        stage('Build Application') {
            steps {
                sh 'docker compose build'
            }
        }

        stage('Start Application') {
            steps {
                sh 'docker compose up -d --wait'
            }
        }

        
        stage('Test PHP Syntax') {
            steps {
                dir('.') {
                    sh 'docker compose exec -T web php -l /var/www/html/index.php'
                }
            }
        }

        stage('Test MySQL Connection') {
            steps {
                dir('.') {
                    sh '''
                        docker compose exec -T web php -r '
                            $pdo = new PDO(
                                "mysql:host=db;dbname=demo_db;charset=utf8mb4",
                                "demo_user",
                                "demo_pass"
                            );
                            echo "MySQL connection successful!\\n";
                        '
                    '''
                }
            }
        }

        
        stage('Test HTTP Response') {
            steps {
                sh '''
                    docker compose exec -T web php -r '
                        $response = file_get_contents("http://127.0.0.1/");
                        if ($response === false) {
                            fwrite(STDERR, "HTTP test failed\\n");
                            exit(1);
                        }
                        echo "HTTP test passed!\\n";
                    '
                '''
            }
        }


    }
}