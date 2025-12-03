FROM node:18-alpine

WORKDIR /app

# Install dependencies
COPY package.json package-lock.json* ./
RUN npm install

# Copy application
COPY . .

# Expose port
EXPOSE 3000

# Initialize database and start server
CMD ["sh", "-c", "sleep 5 && node init_db.js && node server.js"]

