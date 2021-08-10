<template>
  <div class="text-center">
    <v-btn class="ml-2" color="blue" dark @click="sheet = !sheet"> Give Nya! </v-btn>
    <v-bottom-sheet v-model="sheet">
      <v-sheet class="text-center" height="400px">
        <v-btn class="mt-6" text color="red" @click="sheet = !sheet">
          close
        </v-btn>
        <div class="py-3 mx-12">
          <p class="pa-2">Select your amount: {{amount}} Nya</p>
          <v-text-field v-model="keys.privateKey" label="Private Key" />
          <p>{{recPublicKey}}</p>
          <br>
          <div class="d-inline-flex">
            <v-btn @click="amount = 1; data='upvote:1:'" class="mx-2">1 Nya</v-btn>
            <v-btn @click="amount = 10; data='upvote:10:'" class="mx-2">10 Nya</v-btn>
            <v-btn @click="amount = 25; data='upvote:25:'" class="mx-2">25 Nya</v-btn>
            <v-btn @click="amount = 50; data='upvote:50:'" class="mx-2">50 Nya</v-btn>
            <v-btn @click="amount = 100; data='upvote:100:'" class="mx-2">100 Nya</v-btn>
          </div>
        </div>
        <v-btn @click="listen" color="red">Give Nya!</v-btn>
      </v-sheet>
    </v-bottom-sheet>
  </div>
</template>

<script>
var nya = require("nyzostrings/src");
const nyzoStringTransaction = require("nyzostrings/src/NyzoStringTransaction");
const NyzoString = require("nyzostrings/src/NyzoString");
const { nyzoStringEncoder } = require("nyzostrings/src/NyzoStringEncoder");
const NyzoStringEncoder = require("nyzostrings/src/NyzoStringEncoder");

const cheerio = require("cheerio");

const Int64 = require("node-int64");

const Tran = require("@/js/Transaction.js");
import axios from "axios";
export default {
    props: ["recPublicKey", "usersID", "postsID"],
  data() {
    return {
      sheet: false,
      amount: 1,
      data: "upvote:1:",
      keys: {
        privateKey: "",
      },
    };
  },
  mounted() {
  },
  methods: {
    async listen() {
      if (this.$store.state.loggedIn == false) {
        alert('Please login to give Nyas!');
        this.$router.push({name: 'Feed'})
        return 0;
      }
      var testPK = this.keys.privateKey;
      var teskPublic = this.$store.state.currentUser.usersPublicKey;

      var testRecv = this.recPublicKey;

      var userData = "TT:NYA:" + this.amount;

      let nyzoPrice = 1;

      var enData = this.toUTF8Array(userData);

      var pkString = NyzoStringEncoder.nyzoStringEncoder.decode(testPK);

      var recvString = NyzoStringEncoder.nyzoStringEncoder.decode(testRecv);

      var publicString = NyzoStringEncoder.nyzoStringEncoder.decode(teskPublic);

      var re = await this.httpGet("https://nyzo.today/api/frozen_edge");
      //var site = cheerio.load(re);
      console.log(re);
      //var height = site("pre").contents().first().text().substring(6);
      var height = re['height'];
      console.log("h:" + height);
      //height = 12979620;

      //var hash = site("div").contents().next().first().text().substring(4);
      var hash = re['hash']
      console.log(hash.toString(16));
      var newH = hash.toString(16).split("-").join("");
      //hash = "f56a4c11fa06c7a5893232e8cbc78bc12c9d8849eb1e3e6cc44f7921118fe582";
      console.log("newHash: " + newH);
      var bytesHex = Buffer.from(newH.toString(16), "hex");

      console.log(bytesHex);

      var hex = parseInt(height).toString(16);

      var d = new Date();
      var n = d.getTime() + 50000;
      var timeStamp = "0x" + n.toString(16);
      console.log(timeStamp);
      //var timeStamp = 0x17af7bf8bd9;

      var amountHex = nyzoPrice.toString(16);

      console.log("rec: " + recvString.getIdentifier());

      console.log("timestamp: " + timeStamp);
      console.log(parseInt(height).toString(16));
      console.log(new Int64(hex).toString());
      //console.log("hash height: " + hex);

      let t = new Tran();
      t.setTimestamp(timeStamp);
      t.setAmount(nyzoPrice);
      t.setRecipientIdentifier(recvString.getIdentifier());
      t.setPreviousHashHeight(height);
      t.setPreviousBlockHash(bytesHex);
      t.setSenderData(enData);

      t.sign(pkString.getSeed());

      console.log("hex bytes: " + this.toHexString(t.getBytes(false)));

      console.log(t);

      //Test convert
      console.log(this.toHexString(timeStamp));

      //console.log(re.search('<div>height</div><div>([^<]*)</div>'));

      //var testTransaction = new nyzoStringTransaction.NyzoStringTransaction(2,100,1,testRecv)
      console.log("timestamp: " + timeStamp);
      console.log("last height: " + height);
      console.log("last hash: " + hash);
      console.log("amount: " + amountHex);
      console.log("senderId: " + this.toHexString(publicString.getIdentifier()));
      console.log("recvIdentifier: " + this.toHexString(recvString.getIdentifier()));
      console.log("Data: " + userData);
      console.log("signed bytes: " + t.getBytes(true));

      console.log(this.toHexString(t.getBytes(false)));

      //var a = new nyzoStringTransaction.NyzoStringTransaction(2,timeStamp,amount,recvString.getIdentifier(),height,bytesHex,publicString.getIdentifier(),enData,t.getBytes(true));
      var a = nyzoStringTransaction.NyzoStringTransaction.fromByteBuffer(
        t.getBytes(true)
      );

      console.log(a);

      var tx2 = NyzoStringEncoder.nyzoStringEncoder.encode(a);
      console.log(tx2);
      let dataFin = this.data + this.postsID;
      let response = await this.$store.dispatch('txSubmit', {usersID: this.usersID, txString: tx2, Data: dataFin});
      if (response['error'] == 'none') {
        alert('Upvote successful');
      }
    },
    ascii_to_hexa(str) {
      var arr1 = [];
      for (var n = 0, l = str.length; n < l; n++) {
        var hex = Number(str.charCodeAt(n)).toString(16);
        arr1.push(hex);
      }
      return arr1.join("");
    },
    toHexString(byteArray) {
      return Array.from(byteArray, function (byte) {
        return ("0" + (byte & 0xff).toString(16)).slice(-2);
      }).join("");
    },
    toUTF8Array(str) {
      var utf8 = [];
      for (var i = 0; i < str.length; i++) {
        var charcode = str.charCodeAt(i);
        if (charcode < 0x80) utf8.push(charcode);
        else if (charcode < 0x800) {
          utf8.push(0xc0 | (charcode >> 6), 0x80 | (charcode & 0x3f));
        } else if (charcode < 0xd800 || charcode >= 0xe000) {
          utf8.push(
            0xe0 | (charcode >> 12),
            0x80 | ((charcode >> 6) & 0x3f),
            0x80 | (charcode & 0x3f)
          );
        }
        // surrogate pair
        else {
          i++;
          // UTF-16 encodes 0x10000-0x10FFFF by
          // subtracting 0x10000 and splitting the
          // 20 bits of 0x0-0xFFFFF into two halves
          charcode =
            0x10000 +
            (((charcode & 0x3ff) << 10) | (str.charCodeAt(i) & 0x3ff));
          utf8.push(
            0xf0 | (charcode >> 18),
            0x80 | ((charcode >> 12) & 0x3f),
            0x80 | ((charcode >> 6) & 0x3f),
            0x80 | (charcode & 0x3f)
          );
        }
      }
      return utf8;
    },
    async httpGet(theUrl) {
      let res = await axios.get(theUrl);
      console.log(res.data);
      return res.data;
    },
  },
};
</script>

<style>
</style>