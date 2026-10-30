
pipeline {
    agent any

    // Check GitHub for changes approximately every minute
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
            echo 'Application deployed successfully!'
            echo 'Open http://localhost:8081'
        }

        failure {
            echo 'Deployment failed. Check Console Output.'
        }
    }
}
