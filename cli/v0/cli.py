#!/usr/bin/env python3
import requests
import sys
import json

def eattillspace(s):
  splt=s.find(' ')
  if(splt==-1):
    return s, ''
  return s[0:splt], s[splt+1:]


def lsusr(host):
  url = host+"/api/v0/users.php"
  payload={}
  headers={}
  response=requests.request("GET", url, headers=headers, data=payload)
  rsp=response.json()
  usrs=rsp['द']
  for u in usrs:
    print(u)


def mkusr(host,un):
  if un=='':
    print('username empty')
    return
  url = host+"/api/v0/users.php"
  payload = json.dumps({
    "username": un
  })
  headers = {
    'Content-Type': 'application/json'
  }
  response = requests.request("POST", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def rmusr(host,un):
  if un=='':
    print('username empty')
    return
  url = host+"/api/v0/users.php/"+un
  payload={}
  headers = {}
  response = requests.request("DELETE", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def lsroom(host):
  url = host+"/api/v0/chatrooms.php"
  payload={}
  headers={}
  response=requests.request("GET", url, headers=headers, data=payload)
  rsp=response.json()
  rooms=rsp['द']
  for r in rooms:
    print('[%s] %s' % (r['name'], r['desc']))


def mkroom(host,rn,rd):
  if rn=='':
    print('roomname empty')
    return
  if rd=='':
    print('roomdesc empty')
    return
  url = host+"/chatrooms.php"
  payload = json.dumps({
    "name": rn,
    "desc": rd
  })
  headers = {
    'Content-Type': 'application/json'
  }
  response = requests.request("POST", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def rmroom(host,rn):
  if rn=='':
    print('roomname empty')
    return
  url = host+"/api/v0/chatrooms.php/"+rn
  payload={}
  headers = {}
  response = requests.request("DELETE", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def mkroomusr(host,rn,un):
  if rn=='':
    print('roomname empty')
    return
  if un=='':
    print('username empty')
    return
  url = host+"/api/v0/roomusers.php"
  payload = json.dumps({
    "username": un,
    "roomname": rn
  })
  headers = {
    'Content-Type': 'application/json'
  }
  response = requests.request("POST", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def lsroomusr(host,rn):
  if rn=='':
    print('roomname empty')
    return
  url = host+"/api/v0/roomusers.php/"+rn
  payload={}
  headers={}
  response = requests.request("GET", url, headers=headers, data=payload)
  rsp=response.json()
  crus=rsp['द']
  for u in crus:
    print(u)


def rmroomusr(host,rn,un):
  if rn=='':
    print('roomname empty')
    return
  if un=='':
    print('username empty')
    return
  url = '%s/api/v0/roomusers.php/%s/%s' % (host, rn, un)
  payload={}
  headers = {}
  response = requests.request("DELETE", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def sndmsg(host,rn,un,msg):
  if rn=='':
    print('roomname empty')
    return
  if un=='':
    print('username empty')
    return
  if msg=='':
    print('message empty')
    return
  url = host+"/api/v0/sendmesg.php"
  payload = json.dumps({
    "r": rn,
    "u": un,
    "m": msg
  })
  headers = {
    'Content-Type': 'application/json'
  }
  response = requests.request("POST", url, headers=headers, data=payload)
  rsp=response.json()
  print(rsp['हे'])


def rcvmsg(host,rn,un):
  if rn=='':
    print('roomname empty')
    return
  if un=='':
    print('username empty')
    return
  url = host+"/api/v0/recvmesg.php"
  payload = json.dumps({
    "r": rn,
    "u": un
  })
  headers = {
    'Content-Type': 'application/json'
  }
  response = requests.request("POST", url, headers=headers, data=payload)
  rsp=response.json()
  for m in rsp['द']:
    print('%s: %s'%(m['u'], m['m']))


def repl(h):
  while True:
    cli=input('-> ')
    parts=eattillspace(cli)
    if parts[0]=='lsusr':
      lsusr(h)
    elif parts[0]=='mkusr':
      mkusr(h,parts[1])
    elif parts[0]=='rmusr':
      rmusr(h,parts[1])
    elif parts[0]=='lsroom':
      lsroom(h)
    elif parts[0]=='mkroom':
      rn,rd=eattillspace(parts[1])
      mkroom(h,rn,rd)
    elif parts[0]=='rmroom':
      rmroom(h,parts[1])
    elif parts[0]=='mkroomusr':
      rn,un=eattillspace(parts[1])
      mkroomusr(h,rn,un)
    elif parts[0]=='lsroomusr':
      lsroomusr(h,parts[1])
    elif parts[0]=='rmroomusr':
      rn,un=eattillspace(parts[1])
      rmroomusr(h,rn,un)
    elif parts[0]=='sndmsg':
      rn,cdr=eattillspace(parts[1])
      un,msg=eattillspace(cdr)
      sndmsg(h,rn,un,msg)
    elif parts[0]=='rcvmsg':
      rn,un=eattillspace(parts[1])
      rcvmsg(h,rn,un)
    elif parts[0]=='q':
      break
    else:
      print('unparseable command line: '+cli)

# flow
if len(sys.argv)!=2:
  print('usage: ./cli.py http://localhost:8000')
  raise SystemExit

repl(sys.argv[1])

