import type { NextApiRequest, NextApiResponse } from 'next';
import fetch from 'node-fetch';

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  const { url } = req.body;

  if (!url) {
    return res.status(400).json({ error: 'URL required' });
  }

  // VULNERABILITY: SSRF
  // No validation of URL, can access internal services
  try {
    const response = await fetch(url, {
      method: 'GET',
      headers: {
        'User-Agent': 'Next.js App'
      },
      // VULNERABILITY: No redirect protection
      redirect: 'follow'
    });

    const text = await response.text();
    res.json({
      url,
      status: response.status,
      content: text.substring(0, 1000) // Limit response
    });
  } catch (error: any) {
    res.status(500).json({ error: error.message });
  }
}

