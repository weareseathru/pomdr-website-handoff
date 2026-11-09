#!/usr/bin/env python3
"""After crawl.mjs: the original size of every photo the crawl found.

Repo photos are read from New-Build/public/assets/img. Database photos
(dogs, team) are asked of the image host, whose format=json transform
reports the original's width, height and bytes without downloading it.
Cloudflare refuses Python's default user agent, hence the header.

    python3 docs/photo-pass/tools/measure.py .playwright-mcp/study/photos
"""
import json, os, re, sys, urllib.request, concurrent.futures as cf
from urllib.parse import urlparse

S = sys.argv[1]
H = {'User-Agent': 'Mozilla/5.0 (POMDR photo audit)'}
rows = [r for r in json.load(open(f'{S}/inventory.json')) if r.get('src')]
keys = {re.sub(r'^/cdn-cgi/image/[^/]+/', '', urlparse(r['src']).path).lstrip('/')
        for r in rows if urlparse(r['src']).netloc == 'images.pomdr.org'}


def meta(k):
    for _ in range(3):
        try:
            u = f'https://images.pomdr.org/cdn-cgi/image/format=json/{k}'
            j = json.loads(urllib.request.urlopen(urllib.request.Request(u, headers=H), timeout=60).read())['original']
            return k, {'w': j['width'], 'h': j['height'], 'kb': round(j['file_size'] / 1024)}
        except Exception as e:
            err = str(e)[:80]
    return k, {'err': err}


with cf.ThreadPoolExecutor(8) as ex:
    r2 = dict(ex.map(meta, sorted(keys)))
json.dump(r2, open(f'{S}/r2-meta.json', 'w'), indent=0)

from PIL import Image
d = 'New-Build/public/assets/img'
loc = {}
for f in os.listdir(d):
    if f.lower().endswith(('.jpg', '.jpeg', '.png', '.webp')):
        im = Image.open(os.path.join(d, f))
        loc[f] = {'w': im.width, 'h': im.height, 'kb': round(os.path.getsize(os.path.join(d, f)) / 1024)}
json.dump(loc, open(f'{S}/local-meta.json', 'w'), indent=0)
print('database photos', len(r2), 'errors', sum('err' in v for v in r2.values()), '| repo photos', len(loc))
