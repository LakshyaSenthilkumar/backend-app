pipeline {
    agent any

    stages {
        stage('Test SSH to Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh '''
                    ssh -o StrictHostKeyChecking=no ec2-user@10.0.1.105 \
                    "echo CONNECTED FROM JENKINS"
                    '''
                }
            }
        }

        stage('Deploy Backend') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh '''
                    ssh ec2-user@10.0.1.105 << EOF
                    cd /var/www/html
                    sudo git pull origin main
                    sudo systemctl restart httpd
                    EOF
                    '''
                }
            }
        }
    }
}
