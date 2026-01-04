pipeline {
    agent any
    stages {
        stage('Detect Active Backend') {
            steps {
                script {
                    // Try GREEN first
                    def greenStatus = sh(
                        script: "ssh -o StrictHostKeyChecking=no ec2-user@10.0.1.245 'cat /var/www/html/backend-app/ENVIRONMENT' || true",
                        returnStdout: true
                    ).trim()

                    if (greenStatus == "LIVE") {
                        env.BACKEND_PRIVATE_IP = "10.0.1.245"
                        echo "GREEN is LIVE"
                    } else {
                        env.BACKEND_PRIVATE_IP = "10.0.1.105"
                        echo "BLUE is LIVE"
                    }
                }
            }
        }

        stage('Deploy Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ec2-user@${BACKEND_PRIVATE_IP} '
                        cd /var/www/html/backend-app
                        git pull origin main
                    '
                    """
                }
            }
        }
    }
}
