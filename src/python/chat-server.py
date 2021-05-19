import asyncio
import websockets
import pymysql
import json
from datetime import datetime
from http.server import BaseHTTPRequestHandler, HTTPServer

USERS = set()

async def addUser(websocket):
    USERS.add(websocket)

async def removeUser(websocket):
    USERS.remove(websocket)

async def chat(websocket, path):
    await addUser(websocket)

    try:
        while True:
            message = await websocket.recv() # принимаем сообщение
            message = json.loads(message)
            print(message)

            user_login = message["user_login"]
            user_full_name = message["user_full_name"]
            task_id = message["task_id"]
            message_text = message["message"]
            current_date = str(datetime.today().strftime('%Y-%m-%d'))

            connection = pymysql.connect(
                host='localhost',
                user='root',
                password='root',
                db='diplom',
                charset='utf8mb4',
                cursorclass=pymysql.cursors.DictCursor
            )

            with connection.cursor() as cursor:
                sql = "INSERT INTO chat (user_login, user_full_name, task_id, message, date) VALUES (%s,%s,%s,%s,%s)"
                cursor.execute(sql, (user_login, user_full_name, task_id, message_text, current_date))
            connection.commit()

            # with connection.cursor() as cursor:
            #     # Read a single record
            #     sql = "SELECT `full_name` FROM `users` WHERE `id`=%s"
            #     cursor.execute(sql, (user_id,))
            #     result = cursor.fetchone()
            #     message.update(result)
            
            message = json.dumps(message)
            # for user in USERS:
            #     if user != websocket:
            #         await user.send(message["message"])
            await asyncio.wait([user.send(message) for user in USERS]) # отаправляет всем пользователям в чате сообщение
    finally:
        await removeUser(websocket)

start_server = websockets.serve(chat, '127.0.0.1', 7500)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()