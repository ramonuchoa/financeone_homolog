WebP Express 0.14.21. Conversion triggered using bulk conversion, 2019-07-23 03:09:59

*WebP Convert 2.1.4*  ignited.
- PHP version: 7.1.8
- Server software: nginx/1.12.1

Stack converter ignited
Destination folder does not exist. Creating folder: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/12

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2018/12/fintech.jpg
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/12/fintech.jpg.webp
- log-call-arguments: true
- converters: (array of 5 items)

The following options have not been explicitly set, so using the following defaults:
- converter-options: (empty array)
- shuffle: false
- preferred-converters: (empty array)
- extra-converters: (empty array)

The following options were supplied and are passed on to the converters in the stack:
- default-quality: 70
- encoding: "auto"
- max-quality: 80
- metadata: "none"
- near-lossless: 60
- quality: "auto"
------------


*Trying: vips* 

**Error: ** **Required Vips extension is not available.** 
Required Vips extension is not available.
vips failed in 2 ms

*Trying: wpc* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2018/12/fintech.jpg
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/12/fintech.jpg.webp
- default-quality: 70
- encoding: "auto"
- log-call-arguments: true
- max-quality: 80
- metadata: "none"
- near-lossless: 60
- quality: "auto"
- api-key: *****
- api-url: *****
- api-version: 1
- crypt-api-key-in-transfer: false

The following options have not been explicitly set, so using the following defaults:
- alpha-quality: 85
- auto-filter: false
- low-memory: false
- method: 6
- preset: "none"
- size-in-percentage: null (not set)
- skip: false
- use-nice: false
- secret: ""
- url: ""
------------

Quality of source is 90. This is higher than max-quality, so using max-quality instead (80)

**Error: ** **Access denied. Access denied** 
Access denied. Access denied
wpc failed in 325 ms

*Trying: imagemagick* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2018/12/fintech.jpg
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/12/fintech.jpg.webp
- default-quality: 70
- encoding: "auto"
- log-call-arguments: true
- max-quality: 80
- metadata: "none"
- quality: "auto"
- use-nice: true

The following options have not been explicitly set, so using the following defaults:
- alpha-quality: 85
- auto-filter: false
- low-memory: false
- method: 6
- skip: false

The following options were supplied but are ignored because they are not supported by this converter:
- near-lossless
------------

Encoding is set to auto - converting to both lossless and lossy and selecting the smallest file

Converting to lossy
Version: ImageMagick 6.9.5-9 Q16 x86_64 2016-10-21 http://www.imagemagick.org
Quality of source is 90. This is higher than max-quality, so using max-quality instead (80)
using nice
Executing command: nice convert -quality '80' -strip -define webp:alpha-quality=85 -define webp:method=6 '[doc-root]/wp-content/uploads/2018/12/fintech.jpg' 'webp:[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/12/fintech.jpg.webp.lossy.webp'
success
Reduction: 73% (went from 38 kb to 10 kb)

Converting to lossless
Version: ImageMagick 6.9.5-9 Q16 x86_64 2016-10-21 http://www.imagemagick.org
using nice
Executing command: nice convert -quality '80' -define webp:lossless=true -strip -define webp:alpha-quality=85 -define webp:method=6 '[doc-root]/wp-content/uploads/2018/12/fintech.jpg' 'webp:[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2018/12/fintech.jpg.webp.lossless.webp'
success
Reduction: -276% (went from 38 kb to 142 kb)

Picking lossy
imagemagick succeeded :)

Converted image in 1331 ms, reducing file size with 73% (went from 38 kb to 10 kb)
