pipeline {
    agent any

    environment {
        BACKEND_PRIVATE_IP = "10.0.1.245"
    }

    stages {
        stage('Test SSH to Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ec2-user@10.0.1.105 'echo CONNECTED FROM JENKINS'
                    """
                }
            }
        }

        stage('Deploy Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ec2-user@10.0.1.105 '
                        cd /var/www/html
                        git pull origin main
                    '
                    """
                }
            }
        }
    }
}

