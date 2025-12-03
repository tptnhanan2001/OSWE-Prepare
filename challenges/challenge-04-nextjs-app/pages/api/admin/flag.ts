import type { NextApiRequest, NextApiResponse } from 'next';
import jwt from 'jsonwebtoken';

const JWT_SECRET = process.env.JWT_SECRET || 'secret_key';

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method !== 'GET') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  const token = req.headers.authorization?.split(' ')[1] || req.cookies.token;

  if (!token) {
    return res.status(401).json({ error: 'No token provided' });
  }

  try {
    // VULNERABILITY: JWT Algorithm Confusion
    // Doesn't specify algorithm
    const decoded: any = jwt.verify(token, JWT_SECRET);
    
    if (decoded.role !== 'admin') {
      return res.status(403).json({ error: 'Admin access required' });
    }

    // Flag stored in database
    const flag = 'OSWE{SSRF_XXE_GraphQL_SSTI_JWT_Chain_Success!}';
    res.json({ flag });
  } catch (error: any) {
    res.status(401).json({ error: 'Invalid token' });
  }
}

