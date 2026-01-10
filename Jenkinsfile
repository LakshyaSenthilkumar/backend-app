pipeline {
    agent any

    environment {
        BLUE_IP  = "10.0.1.105"
        GREEN_IP = "10.0.1.245"
    }

    stages {

        stage('Detect LIVE Backend') {
            steps {
                script {
                    def greenEnv = sh(
                        script: "ssh -o StrictHostKeyChecking=no ec2-user@${GREEN_IP} 'cat /var/www/html/backend-app/ENVIRONMENT' || echo UNDER_WORK",
                        returnStdout: true
                    ).trim()

                    if (greenEnv == "LIVE") {
                        env.LIVE_IP = GREEN_IP
                        env.IDLE_IP = BLUE_IP
                        echo "🟢 GREEN is currently LIVE"
                    } else {
                        env.LIVE_IP = BLUE_IP
                        env.IDLE_IP = GREEN_IP
                        echo "🔵 BLUE is currently LIVE"
                    }
                }
            }
        }

        stage('Deploy to IDLE Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh ec2-user@${IDLE_IP} '
                        cd /var/www/html/backend-app
                        git pull origin main
                        echo UNDER_WORK | sudo tee ENVIRONMENT
                    '
                    """
                }
            }
        }

        stage('Switch Traffic (Blue-Green Flip)') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh ec2-user@${IDLE_IP} '
                        echo LIVE | sudo tee /var/www/html/backend-app/ENVIRONMENT
                    '
                    ssh ec2-user@${LIVE_IP} '
                        echo UNDER_WORK | sudo tee /var/www/html/backend-app/ENVIRONMENT
                    '
                    '
                    """
                }
            }
        }
    }

    post {
        success {
            echo "✅ Blue–Green deployment completed successfully"
        }
    }
}
