
pipeline {
    agent any

    triggers {
        pollSCM('H/1 * * * *')
    }

    stages {
        stage('Checkout Git') {
            steps {
                checkout scm
                echo 'Latest GitHub code checked out.'
            }
        }

        stage('Check Docker') {
            steps {
                sh 'docker version'
                sh 'docker compose version'
            }
        }

        stage('PHP Syntax Check') {
            steps {
                dir(env.WORKSPACE) {
                    sh '''
                        echo "Checking project directory:"
                        pwd
                        ls -la

                        docker run --rm \
                        -v "$WORKSPACE:/app" \
                        -w /app \
                        php:8.3-cli \
                        sh -c 'find src -name "*.php" -exec php -l {} \\;'
                    '''
                }
            }
        }
    //register.php file is added to the project and tested in the pipeline. it checks the syntax of the register.php file and runs it to ensure that it works correctly. This is important for ensuring that the registration functionality of the application is working as expected.
        stage('PHP Tests') {
            steps {
                sh '''
                    docker run --rm \
                    -v "$WORKSPACE:/app" \
                    -w /app \
                    php:8.3-cli \
                    php src/register.php
                '''
            }
        }

        stage('Build and Deploy') {
            steps {
                sh 'docker compose up -d --build'
            }
        }

        stage('Check Containers') {
            steps {
                sh 'docker compose ps'
            }
        }
    }

    post {
        success {
            echo 'PHP tests passed and application deployed successfully!'
            echo 'Open http://localhost:8081'
        }

        failure {
            echo 'Pipeline failed. Check Console Output.'
        }
    }
}
