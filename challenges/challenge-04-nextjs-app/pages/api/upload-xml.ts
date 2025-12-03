import type { NextApiRequest, NextApiResponse } from 'next';
import { parseString } from 'xml2js';

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  const { xml } = req.body;

  if (!xml) {
    return res.status(400).json({ error: 'XML required' });
  }

  // VULNERABILITY: XXE (XML External Entity)
  // xml2js with default options allows external entities
  try {
    parseString(xml, (err, result) => {
      if (err) {
        return res.status(400).json({ error: 'Invalid XML' });
      }
      res.json({ parsed: result });
    });
  } catch (error: any) {
    res.status(500).json({ error: error.message });
  }
}

