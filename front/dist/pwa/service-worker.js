if(!self.define){let n,e={};const i=(i,o)=>(i=new URL(i+".js",o).href,e[i]||new Promise((e=>{if("document"in self){const n=document.createElement("script");n.src=i,n.onload=e,document.head.appendChild(n)}else n=i,importScripts(i),e()})).then((()=>{let n=e[i];if(!n)throw new Error(`Module ${i} didn’t register its module`);return n})));self.define=(o,f)=>{const s=n||("document"in self?document.currentScript.src:"")||location.href;if(e[s])return;let l={};const r=n=>i(n,s),c={module:{uri:s},exports:l,require:r};e[s]=Promise.all(o.map((n=>c[n]||r(n)))).then((n=>(f(...n),l)))}}define(["./workbox-ca2b596c"],(function(n){"use strict";n.setCacheNameDetails({prefix:"front"}),self.addEventListener("message",(n=>{n.data&&"SKIP_WAITING"===n.data.type&&self.skipWaiting()})),n.precacheAndRoute([{url:"css/app.ac86cd7e.css",revision:null},{url:"css/vendor.862375d3.css",revision:null},{url:"favicon.ico",revision:"f4facfeaed834544d622544acfbb7722"},{url:"fonts/KFOkCnqEu92Fr1MmgVxIIzQ.68bb21d0.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmEU9fBBc-.48af7707.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmSU5fBBc-.c2f7ab22.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmWUlfBBc-.77ecb942.woff",revision:null},{url:"fonts/KFOlCnqEu92Fr1MmYUtfBBc-.f5677eb2.woff",revision:null},{url:"fonts/KFOmCnqEu92Fr1Mu4mxM.f1e2a767.woff",revision:null},{url:"fonts/LDItaoyNOAY6Uewc665JcIzCKsKc_M9flwmJ.5d681e2e.woff",revision:null},{url:"fonts/LDItaoyNOAY6Uewc665JcIzCKsKc_M9flwmPq_HTTw.c380809f.woff2",revision:null},{url:"fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNa.4d73cb90.woff",revision:null},{url:"fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNcIhQ8tQ.0383092b.woff2",revision:null},{url:"fonts/gok-H7zzDkdnRel8-DQ6KAXJ69wP1tGnf4ZGhUcY.f882956f.woff",revision:null},{url:"fonts/gok-H7zzDkdnRel8-DQ6KAXJ69wP1tGnf4ZGhUcel5euIg.6f420cf1.woff2",revision:null},{url:"fonts/oPWQ_lt5nv4pWNJpghLP75WiFR4kLh3kvmvRImcycg.219aa914.woff2",revision:null},{url:"fonts/oPWQ_lt5nv4pWNJpghLP75WiFR4kLh3kvmvX.3a4004a4.woff",revision:null},{url:"icons/apple-icon-120x120.png",revision:"d082235f6e6d2109e84e397f66fa868d"},{url:"icons/apple-icon-152x152.png",revision:"3c728ce3e709b7395be487becf76283a"},{url:"icons/apple-icon-167x167.png",revision:"3fec89672a18e4b402ede58646917c2d"},{url:"icons/apple-icon-180x180.png",revision:"aa47843bd47f34b7ca4b99f65dd25955"},{url:"icons/favicon-128x128.png",revision:"ab92df0270f054ca388127c9703a4911"},{url:"icons/favicon-16x16.png",revision:"e4b046d41e08e6fa06626d6410ab381d"},{url:"icons/favicon-32x32.png",revision:"410858b01fa6d3d66b7bf21447c5f1fc"},{url:"icons/favicon-96x96.png",revision:"db2bde7f824fb4057ffd1c42f6ed756e"},{url:"icons/icon-128x128.png",revision:"ab92df0270f054ca388127c9703a4911"},{url:"icons/icon-192x192.png",revision:"7659f0d3e9602e71811f8b7cf2ce0e8e"},{url:"icons/icon-256x256.png",revision:"cf5ad3498fb6fda43bdafd3c6ce9b824"},{url:"icons/icon-384x384.png",revision:"fdfc1b3612b6833a27a7b260c9990247"},{url:"icons/icon-512x512.png",revision:"2c2dc987945806196bd18cb6028d8bf4"},{url:"icons/ms-icon-144x144.png",revision:"8de1b0e67a62b881cd22d935f102a0e6"},{url:"icons/safari-pinned-tab.svg",revision:"3e4c3730b00c89591de9505efb73afd3"},{url:"imagenes/logo.png",revision:"1f22160558e8f2e50e976a8dd9bf2ffc"},{url:"index.html",revision:"38a160a88741a4a647bf4b929805ece9"},{url:"js/165.c3b92e34.js",revision:null},{url:"js/862.a8b2801d.js",revision:null},{url:"js/944.2a6c6997.js",revision:null},{url:"js/app.5a63634a.js",revision:null},{url:"js/vendor.8cc2f22e.js",revision:null},{url:"logo.jpg",revision:"1f22160558e8f2e50e976a8dd9bf2ffc"},{url:"manifest.json",revision:"186c79e34d24a23867f500d05c4cd651"}],{}),n.registerRoute(new n.NavigationRoute(n.createHandlerBoundToURL("index.html"),{denylist:[/service-worker\.js$/,/workbox-(.)*\.js$/]}))}));
