pipeline {
    agent any

    environment {
        GREEN_IP = "10.0.1.245"
        BLUE_IP  = "10.0.1.105"
    }

    stages {

        stage('Detect Active Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    script {
                        def greenEnv = sh(
                            script: """
                            ssh -o StrictHostKeyChecking=no ec2-user@${GREEN_IP} \
                            'cat /var/www/html/backend-app/ENVIRONMENT 2>/dev/null || echo UNDER_WORK'
                            """,
                            returnStdout: true
                        ).trim()

                        echo "GREEN ENV = ${greenEnv}"

                        if (greenEnv == "LIVE") {
                            env.LIVE_IP = env.GREEN_IP
                            env.IDLE_IP = env.BLUE_IP
                            echo "🟢 GREEN is LIVE"
                        } else {
                            env.LIVE_IP = env.BLUE_IP
                            env.IDLE_IP = env.GREEN_IP
                            echo "🔵 BLUE is LIVE"
                        }
                    }
                }
            }
        }

        stage('Deploy to IDLE Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    echo "Deploying to IDLE backend: ${IDLE_IP}"

                    ssh -o StrictHostKeyChecking=no ec2-user@${IDLE_IP} '
                        cd /var/www/html/backend-app
                        git pull origin main
                        echo LIVE | sudo tee /var/www/html/backend-app/ENVIRONMENT
                    '

                    ssh -o StrictHostKeyChecking=no ec2-user@${LIVE_IP} '
                        echo UNDER_WORK | sudo tee /var/www/html/backend-app/ENVIRONMENT
                    '
                    """
                }
            }
        }

        stage('Verify Switch') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    echo "NEW LIVE BACKEND:"
                    ssh ec2-user@${IDLE_IP} 'cat /var/www/html/backend-app/ENVIRONMENT'

                    echo "OLD BACKEND:"
                    ssh ec2-user@${LIVE_IP} 'cat /var/www/html/backend-app/ENVIRONMENT'
                    """
                }
            }
        }
    }
}
