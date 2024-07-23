pipeline {
	agent none
	stages {
		stage('Integration UI Test') {
			parallel {
				stage('Deploy') {
                    agent any
                    steps {
                        sh 'git config --global --add safe.directory /var/jenkins_home/workspace/SSD'
                        git url: 'http://gitea:3000/Sia-Hong-Liang/2201493.git', branch: 'main'
                        sh './jenkins/scripts/deploy.sh'
                        input message: 'Finished using the web site? (Click "Proceed" to continue)'
                        sh './jenkins/scripts/kill.sh'
                    }
                }
				stage('Headless Browser Test') {
					agent {
                        docker {
                            image 'maven:3-alpine' 
                            args '-v /root/.m2:/root/.m2 --dns 8.8.8.8 --dns 8.8.4.4 --memory=4g --memory-swap=4g --ulimit nofile=8192:8192' 
                        }
                    }
					steps {
                        sh '''
                            sleep 4 &&
                            mvn -B -DskipTests clean package &&
                            mvn test &&
                            sleep 10
                        '''
					}
                    post {
                        always {
                            junit 'target/surefire-reports/*.xml'
                        }
                    }
				}
                stage('OWASP Dependency-Check Vulnerabilities') {
      		        steps {
        		        dependencyCheck additionalArguments: ''' 
                            -o './'
                            -s './'
                            -f 'ALL' 
                            --prettyPrint''', odcInstallation: 'OWASP Dependency-Check Vulnerabilities'
        
        		        dependencyCheckPublisher pattern: 'dependency-check-report.xml'
      		        }
                }
			}
		}
	}
}