from SOAPpy import SOAPProxy

username, password, instance = 'admin', 'admin', 'demo'
proxy, namespace = 'https://username:password@www.service-now.com/'+instance+'/incident.do?SOAP', 'http://www.service-now.com/'

server = SOAPProxy(proxy,namespace)
response = server.get(sys_id = '9c573169c611228700193229fff72400')

for each in response:
	print each