import axios from 'axios';

var nya = require('nyzostrings/src');
const nyzoStringTransaction =  require('nyzostrings/src/NyzoStringTransaction');
const NyzoString = require('nyzostrings/src/NyzoString');
const { nyzoStringEncoder } = require('nyzostrings/src/NyzoStringEncoder');
const NyzoStringEncoder = require('nyzostrings/src/NyzoStringEncoder');

const cheerio = require('cheerio');

const Int64 = require('node-int64');

const Tran = require("./Transaction.js");

function httpGet(theUrl)
{
  let res = axios.get(theUrl);
  return res.data;
}

function listen() {

  var testPK = "key_8dRfF24nn3JB0uzjw22RVER-5-zDCIANsAt~d~tFIWdnXnHkj5eD";
  var teskPublic = "id__87Koyj.XVN1PJeQAFAyzIVdffBHFmQBNgTT7XDas4hCs.hzU27z_";

  var testRecv = "id__87u5E4EXcb0GA4ec75WkYi~0xkWKvMmzmxjGmZzDM.7DM1MySZaC";

  var userData =  "TT:NYA:1";
  
  var enData = toUTF8Array(userData);
  
  var pkString = NyzoStringEncoder.nyzoStringEncoder.decode(testPK);

  var recvString = NyzoStringEncoder.nyzoStringEncoder.decode(testRecv);

  var publicString = NyzoStringEncoder.nyzoStringEncoder.decode(teskPublic);
  
  var re = httpGet("https://client.nyzo.co/frozenEdge");
  var site = cheerio.load(re);

  var height = site('div').contents().first().text().substring(6);
  console.log("h:" + height)
  //height = 12979620;

  var hash = site('div').contents().next().first().text().substring(4);
  console.log(hash.toString(16));
  var newH = hash.toString(16).split('-').join('');
  //hash = "f56a4c11fa06c7a5893232e8cbc78bc12c9d8849eb1e3e6cc44f7921118fe582";
  console.log("newHash: " + newH);
  bytesHex = Buffer.from(newH.toString(16), 'hex');

  console.log(bytesHex);

  var hex = parseInt(height).toString(16);
  
  var d = new Date();
  var n = d.getTime();
  var timeStamp = "0x" + n.toString(16);
  console.log(timeStamp);
  //var timeStamp = 0x17af7bf8bd9;


  var amount = 1;
  var amountHex = amount.toString(16);

  console.log("rec: " + recvString.getIdentifier());

  console.log("timestamp: " + timeStamp);
  console.log(parseInt(height).toString(16));
  console.log(new Int64(hex).toString());
  //console.log("hash height: " + hex);

  let t = new Tran();
  t.setTimestamp(timeStamp);
  t.setAmount(amount);
  t.setRecipientIdentifier(recvString.getIdentifier());
  t.setPreviousHashHeight(height);
  t.setPreviousBlockHash(bytesHex);
  t.setSenderData(enData);

  t.sign(pkString.getSeed());

  console.log("hex bytes: " + toHexString(t.getBytes(false)));

  console.log(t);

  //Test convert
  console.log(toHexString(timeStamp));


  //console.log(re.search('<div>height</div><div>([^<]*)</div>'));

  //var testTransaction = new nyzoStringTransaction.NyzoStringTransaction(2,100,1,testRecv)
  console.log("timestamp: " + timeStamp)
  console.log("last height: " + height);
  console.log("last hash: " + hash);
  console.log("amount: " + amountHex);
  console.log("senderId: " + toHexString(publicString.getIdentifier()));
  console.log("recvIdentifier: " +toHexString(recvString.getIdentifier()));
  console.log("Data: " + userData);
  console.log("signed bytes: " + t.getBytes(true));

  console.log(toHexString(t.getBytes(false)));

  //var a = new nyzoStringTransaction.NyzoStringTransaction(2,timeStamp,amount,recvString.getIdentifier(),height,bytesHex,publicString.getIdentifier(),enData,t.getBytes(true));
  var a = nyzoStringTransaction.NyzoStringTransaction.fromByteBuffer(t.getBytes(true));

  console.log(a);

  var tx2 = NyzoStringEncoder.nyzoStringEncoder.encode(a);
  return tx2;

  console.log(tx2);
/*
  var testTransaction = nyzoStringTransaction.NyzoStringTransaction.fromHex(
    timeStamp,toHexString(amount), 
    toHexString(recvString.getIdentifier()),
    parseInt(height).toString(16), toHexString(bytesHex),toHexString(publicString.getIdentifier()),
     toHexString(enData),toHexString(t.getBytes(true)));
  console.log(testTransaction);
*/
  //var tx = NyzoStringEncoder.nyzoStringEncoder.encode(testTransaction);

  //console.log(tx);

  //console.log(toHexString(testTransaction.getBytes()));

};

function ascii_to_hexa(str)
  {
	var arr1 = [];
	for (var n = 0, l = str.length; n < l; n ++) 
     {
		var hex = Number(str.charCodeAt(n)).toString(16);
		arr1.push(hex);
	 }
	return arr1.join('');
   }

function toHexString(byteArray) {
  return Array.from(byteArray, function(byte) {
    return ('0' + (byte & 0xFF).toString(16)).slice(-2);
  }).join('')
}

function parseHexString(str) { 
  var result = [];
  while (str.length >= 8) { 
      result.push(parseInt(str.substring(0, 8), 16));

      str = str.substring(8, str.length);
  }

  return result;
}

function toUTF8Array(str) {
  var utf8 = [];
  for (var i=0; i < str.length; i++) {
      var charcode = str.charCodeAt(i);
      if (charcode < 0x80) utf8.push(charcode);
      else if (charcode < 0x800) {
          utf8.push(0xc0 | (charcode >> 6), 
                    0x80 | (charcode & 0x3f));
      }
      else if (charcode < 0xd800 || charcode >= 0xe000) {
          utf8.push(0xe0 | (charcode >> 12), 
                    0x80 | ((charcode>>6) & 0x3f), 
                    0x80 | (charcode & 0x3f));
      }
      // surrogate pair
      else {
          i++;
          // UTF-16 encodes 0x10000-0x10FFFF by
          // subtracting 0x10000 and splitting the
          // 20 bits of 0x0-0xFFFFF into two halves
          charcode = 0x10000 + (((charcode & 0x3ff)<<10)
                    | (str.charCodeAt(i) & 0x3ff));
          utf8.push(0xf0 | (charcode >>18), 
                    0x80 | ((charcode>>12) & 0x3f), 
                    0x80 | ((charcode>>6) & 0x3f), 
                    0x80 | (charcode & 0x3f));
      }
  }
  return utf8;
}