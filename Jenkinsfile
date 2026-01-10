pipeline {
    agent any

    environment {
        GREEN_IP = "10.0.1.245"
        BLUE_IP  = "10.0.1.105"
        TG_ARN   = "arn:aws:elasticloadbalancing:us-east-1:665758762277:targetgroup/backend-tg/17417b7bd9774b7b"
        AWS_REGION = "us-east-1"
    }

    stages {

        stage('Detect Live Backend') {
            steps {
                script {
                    def greenEnv = sh(
                        script: "ssh ec2-user@${GREEN_IP} 'cat /var/www/html/backend-app/ENVIRONMENT'",
                        returnStdout: true
                    ).trim()

                    if (greenEnv == "LIVE") {
                        env.CURRENT_LIVE = GREEN_IP
                        env.NEW_LIVE = BLUE_IP
                        echo "🟢 GREEN currently LIVE → switching to BLUE"
                    } else {
                        env.CURRENT_LIVE = BLUE_IP
                        env.NEW_LIVE = GREEN_IP
                        echo "🔵 BLUE currently LIVE → switching to GREEN"
                    }
                }
            }
        }

        stage('Deploy to NEW LIVE') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh ec2-user@${NEW_LIVE} '
                        cd /var/www/html/backend-app
                        git pull origin main
                        echo LIVE | sudo tee ENVIRONMENT
                    '
                    """
                }
            }
        }

        stage('Switch ALB Traffic') {
            steps {
                sh """
                aws elbv2 deregister-targets \
                  --target-group-arn ${TG_ARN} \
                  --targets Id=${CURRENT_LIVE} \
                  --region ${AWS_REGION}

                aws elbv2 register-targets \
                  --target-group-arn ${TG_ARN} \
                  --targets Id=${NEW_LIVE} \
                  --region ${AWS_REGION}
                """
            }
        }

        stage('Mark OLD as UNDER_WORK') {
            steps {
                sshagent(credentials: ['backend-ssh-key']) {
                    sh """
                    ssh ec2-user@${CURRENT_LIVE} '
                        echo UNDER_WORK | sudo tee /var/www/html/backend-app/ENVIRONMENT
                    '
                    """
                }
            }
        }

        stage('Done') {
            steps {
                echo "✅ Traffic switched successfully"
                echo "LIVE backend: ${NEW_LIVE}"
                echo "OLD backend removed from ALB"
            }
        }
    }
}
