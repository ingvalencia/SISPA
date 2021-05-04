from SOAPpy import SOAPProxy

username, password, instance = 'admin', 'admin', 'demo'
proxy, namespace = 'https://username:password@www.service-now.com/'+instance+'/incident.do?SOAP', 'http://www.service-now.com/'

server = SOAPProxy(proxy,namespace)
response = server.getRecords(category = 'Network')

for record in response:
	for item in record:
		print item