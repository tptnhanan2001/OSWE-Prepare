const { MongoClient } = require('mongodb');

const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://mongo:27017/challenge_db';

async function initDatabase() {
    const client = new MongoClient(MONGODB_URI);
    
    try {
        await client.connect();
        const db = client.db('challenge_db');
        
        // Insert users
        await db.collection('users').insertMany([
            { username: 'admin', password: 'admin123', role: 'admin' },
            { username: 'user', password: 'user123', role: 'user' }
        ]);
        
        // Insert products
        await db.collection('products').insertMany([
            { name: 'Laptop', description: 'High-performance laptop', price: 999.99 },
            { name: 'Phone', description: 'Smartphone', price: 599.99 }
        ]);
        
        // Insert flag
        await db.collection('flags').insertOne({
            type: 'admin_flag',
            value: 'OSWE{JWT_PrototypePollution_NoSQL_CommandInjection_Chain!}'
        });
        
        console.log('Database initialized successfully');
    } catch (error) {
        console.error('Error initializing database:', error);
    } finally {
        await client.close();
    }
}

initDatabase();

