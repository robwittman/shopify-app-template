FROM node:9

WORKDIR /app

EXPOSE 3000

CMD ["npm", "run", "start"]