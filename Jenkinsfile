pipeline {
    agent any

    environment {
        BACKEND_PRIVATE_IP = "10.0.1.245"   // GREEN backend private IP
    }

    stages {

        stage('Test SSH to Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ec2-user@${BACKEND_PRIVATE_IP} \
                    'echo CONNECTED FROM GREEN BACKEND'
                    """
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
