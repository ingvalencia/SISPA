from suds.client import Client
url = 'http://www.webservicex.net/globalweather.asmx?WSDL'
client = Client(url)
print client