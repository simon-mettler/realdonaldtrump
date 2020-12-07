import sys, json

# Load the data that PHP sent.
try:
    data = sys.argv[1]
except:
    print "ERROR"
    sys.exit(1)

# Make something with the data to send to PHP.
result = len(data.split()) 

# Send it to stdout (to PHP).
print result 

