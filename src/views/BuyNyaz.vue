<template>
  <v-row>
    <v-col></v-col>
    <v-col>
      <v-card v-show="!isDone" width="600px" color="primary" class="mx-auto pa-3">
        <v-card-title>
          <h2 class="white--text">Buy Nyzo</h2>
        </v-card-title>
        <v-card-text>
          <v-text-field v-model="keys.privateKey" label="Private Wallet Key" />
          <p>We do not save your private wallet key anywhere</p>
          <h2 class="my-3 white--text">Selected amount of Nyzo {{feeAmount/1000000}} for Nya {{nya}}</h2>
          <div class="d-inline-flex">
            <div class="mr-2">
              <h3>1 Nyzo for</h3>
              <v-btn @click="feeAmount = 1000000; data='buy:10'; nya=10">10 Nya</v-btn>
            </div>
            <div class="mx-2">
              <h3>10 Nyzo for</h3>
              <v-btn @click="feeAmount = 10000000; data='buy:125'; nya=125">125 Nya</v-btn>
            </div>
            <div class="mx-2">
              <h3>100 Nyzo for</h3>
              <v-btn @click="feeAmount = 100000000; data='buy:1500'; nya=1500">1500 Nya</v-btn>
            </div>
          </div>
        </v-card-text>
        <v-card-actions>
          <v-btn block @click="listen">Buy Nya!</v-btn>
        </v-card-actions>
      </v-card>
      <v-card v-show="isDone" width="600px" color="primary" class="mx-auto pa-3">
        <v-card-title>
          <h2 class="white--text">Thank you!</h2>
        </v-card-title>
        <v-card-text>
          <p>Your purchase as been submitted to the Nyzo chain! Please allow some time for it to process and your wallet to be updated accordingly</p>
          <p>If you believe an issue has occurred, feel free to contact us.</p>
        </v-card-text>
        <v-card-actions>
          <v-btn block @click="$router.push({name: 'feed'})">Return Home</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col></v-col>
  </v-row>
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
import { mapState } from 'vuex';
export default {
  props: ["recPublicKey"],
  metaInfo: {
    title: 'Buy Nyaz',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  data() {
    return {
      sheet: false,
      isDone: false,
      nya: 10,
      data: "buy:10",
      amount: "",
      feeAmount: 1000000,
      usersID: 1,
      keys: {
        privateKey: "",
      },
    };
  },
  computed: {
    ...mapState('auth', ['currentUser'])
  },
  mounted() {},
  methods: {
    async listen() {
      var testPK = this.keys.privateKey;
      var teskPublic = this.currentUser.usersPublicKey;

      var testRecv = "id__89ng86VtkW_TDC~9WNUnNXGTX7WiH8aFAwTqkNKgTxz-vz_AaE87";

      var userData = this.amount;

      let nyzoPrice = this.feeAmount;

      var enData = this.toUTF8Array(userData);

      var pkString = NyzoStringEncoder.nyzoStringEncoder.decode(testPK);

      var recvString = NyzoStringEncoder.nyzoStringEncoder.decode(testRecv);

      var publicString = NyzoStringEncoder.nyzoStringEncoder.decode(teskPublic);

      var re = await this.httpGet("https://nyzo.today/api/frozen_edge");
      //var site = cheerio.load(re);

      //var height = site("pre").contents().first().text().substring(6);
      var height = re['height'];
      console.log("h:" + height);
      //height = 12979620;
      var hash = re['hash'];
      //var hash = site("div").contents().next().first().text().substring(4);
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
      console.log(
        "senderId: " + this.toHexString(publicString.getIdentifier())
      );
      console.log(
        "recvIdentifier: " + this.toHexString(recvString.getIdentifier())
      );
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
      let response = await this.$store.dispatch("auth/txSubmit", {
        usersID: this.usersID,
        txString: tx2,
        Data: this.data,
      });
      this.isDone = true;
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