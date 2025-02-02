const n = require("tweetnacl");

const SHA256 = require("crypto-js");
const ByteBuffer = require("@/js/byteBuffer.js");

class Transaction {

    constructor() {
        this.timestamp = Date.now();
        this.amount = 0;
        this.recipientIdentifier = new Uint8Array(32);
        this.previousHashHeight = 0;
        this.previousBlockHash = new Uint8Array(32);
        this.senderIdentifier = new Uint8Array(32);
        this.senderData = new Uint8Array(0);
        this.signature = new Uint8Array(64);
    }

    setTimestamp(timestamp) {
        this.timestamp = timestamp;
    }

    setAmount(amount) {
        this.amount = amount;
    }

    setRecipientIdentifier(recipientIdentifier) {
        for (var i = 0; i < 32; i++) {
            this.recipientIdentifier[i] = recipientIdentifier[i];
        }
    }

    setPreviousHashHeight(previousHashHeight) {
        this.previousHashHeight = previousHashHeight;
    }

    setPreviousBlockHash(previousBlockHash) {
        for (var i = 0; i < 32; i++) {
            this.previousBlockHash[i] = previousBlockHash[i];
        }
    }

    setSenderData(senderData) {
        this.senderData = new Uint8Array(Math.min(senderData.length, 32));
        for (var i = 0; i < this.senderData.length; i++) {
            this.senderData[i] = senderData[i];
        }
    }

    sign(seedBytes) {
        var keyPair = n.sign.keyPair.fromSeed(seedBytes);
        for (var i = 0; i < 32; i++) {
            this.senderIdentifier[i] = keyPair.publicKey[i];
        }

        var signature = n.sign.detached(this.getBytes(false), keyPair.secretKey);
        for (var i = 0; i < 64; i++) {
            this.signature[i] = signature[i];
        }
    }

    hex2ab(hex){
		const ab = []
		for (let i = 0; i < hex.length; i += 2) {
			ab.push(parseInt(hex.substr(i, 2), 16))
		}
		return new Uint8Array(ab)
	}

    ab2hex(buf){
		return Array.prototype.map.call(new Uint8Array(buf), x => ('00' + x.toString(16)).slice(-2)).join('')
	}

    methodA(byteArray) {
        return this.hex2ab(SHA256.SHA256(SHA256.SHA256(SHA256.enc.Hex.parse(this.ab2hex(byteArray)))).toString());
    }

    getBytes(includeSignature) {      
        var self = this;
        var forSigning = !includeSignature;

        var buffer = new ByteBuffer(1000);

        buffer.putByte(2);  // transaction type = 2 (standard)
        buffer.putLong(this.timestamp);
        buffer.putLong(this.amount);
        buffer.putBytes(this.recipientIdentifier);

        if (forSigning) {
            buffer.putBytes(this.previousBlockHash);
        } else {
            buffer.putLong(this.previousHashHeight);
        }
        buffer.putBytes(this.senderIdentifier);

        if (forSigning) {
            buffer.putBytes(self.methodA(self.senderData));
        } else {
            buffer.putByte(this.senderData.length);
            buffer.putBytes(this.senderData);
        }

        if (!forSigning) {
            buffer.putBytes(this.signature);
        }

        return buffer.toArray();
    }


    

}


module.exports = Transaction;