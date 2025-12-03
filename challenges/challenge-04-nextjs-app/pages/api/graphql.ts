import type { NextApiRequest, NextApiResponse } from 'next';
import { buildSchema, graphql } from 'graphql';

const schema = buildSchema(`
  type User {
    id: ID!
    username: String!
    email: String
    role: String
  }
  
  type Query {
    user(id: ID!): User
    users: [User]
  }
`);

const root = {
  user: async ({ id }: { id: string }) => {
    // VULNERABILITY: GraphQL Injection
    // Direct use of user input in query
    // In real app, would query database
    return { id, username: 'test', email: 'test@example.com', role: 'user' };
  },
  users: async () => {
    return [];
  }
};

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  const { query, variables } = req.body;

  if (!query) {
    return res.status(400).json({ error: 'Query required' });
  }

  // VULNERABILITY: No validation of GraphQL query
  // Can inject malicious queries
  try {
    const result = await graphql({
      schema,
      source: query,
      rootValue: root,
      variableValues: variables
    });
    res.json(result);
  } catch (error: any) {
    res.status(500).json({ error: error.message });
  }
}

