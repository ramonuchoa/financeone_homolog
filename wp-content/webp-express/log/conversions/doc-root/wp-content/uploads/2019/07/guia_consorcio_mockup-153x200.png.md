WebP Express 0.14.21. Conversion triggered using bulk conversion, 2019-07-23 13:45:14

*WebP Convert 2.1.4*  ignited.
- PHP version: 7.1.8
- Server software: nginx/1.12.1

Stack converter ignited

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png.webp
- log-call-arguments: true
- converters: (array of 5 items)

The following options have not been explicitly set, so using the following defaults:
- converter-options: (empty array)
- shuffle: false
- preferred-converters: (empty array)
- extra-converters: (empty array)

The following options were supplied and are passed on to the converters in the stack:
- alpha-quality: 80
- encoding: "auto"
- metadata: "none"
- near-lossless: 60
- quality: 85
------------


*Trying: vips* 

**Error: ** **Required Vips extension is not available.** 
Required Vips extension is not available.
vips failed in 1 ms

*Trying: wpc* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png.webp
- alpha-quality: 80
- encoding: "auto"
- log-call-arguments: true
- metadata: "none"
- near-lossless: 60
- quality: 85
- api-key: *****
- api-url: *****
- api-version: 1
- crypt-api-key-in-transfer: false

The following options have not been explicitly set, so using the following defaults:
- auto-filter: false
- default-quality: 85
- low-memory: false
- max-quality: 85
- method: 6
- preset: "none"
- size-in-percentage: null (not set)
- skip: false
- use-nice: false
- secret: ""
- url: ""
------------

Quality: 85. 

**Error: ** **Access denied. Access denied** 
Access denied. Access denied
wpc failed in 292 ms

*Trying: imagemagick* 

Options:
------------
The following options have been set explicitly. Note: it is the resulting options after merging down the "jpeg" and "png" options and any converter-prefixed options.
- source: [doc-root]/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png
- destination: [doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png.webp
- alpha-quality: 80
- encoding: "auto"
- log-call-arguments: true
- metadata: "none"
- quality: 85
- use-nice: true

The following options have not been explicitly set, so using the following defaults:
- auto-filter: false
- default-quality: 85
- low-memory: false
- max-quality: 85
- method: 6
- skip: false

The following options were supplied but are ignored because they are not supported by this converter:
- near-lossless
------------

Encoding is set to auto - converting to both lossless and lossy and selecting the smallest file

Converting to lossy
Version: ImageMagick 6.9.5-9 Q16 x86_64 2016-10-21 http://www.imagemagick.org
Quality: 85. 
using nice
Executing command: nice convert -quality '85' -strip -define webp:alpha-quality=80 -define webp:method=6 '[doc-root]/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png' 'webp:[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png.webp.lossy.webp'
success
Reduction: 86% (went from 40 kb to 6 kb)

Converting to lossless
Version: ImageMagick 6.9.5-9 Q16 x86_64 2016-10-21 http://www.imagemagick.org
using nice
Executing command: nice convert -quality '85' -define webp:lossless=true -strip -define webp:alpha-quality=80 -define webp:method=6 '[doc-root]/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png' 'webp:[doc-root]/wp-content/webp-express/webp-images/doc-root/wp-content/uploads/2019/07/guia_consorcio_mockup-153x200.png.webp.lossless.webp'
success
Reduction: 34% (went from 40 kb to 26 kb)

Picking lossy
imagemagick succeeded :)

Converted image in 616 ms, reducing file size with 86% (went from 40 kb to 6 kb)
