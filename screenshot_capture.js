const puppeteer = require('puppeteer');

async function run() {
    let browser = await puppeteer.launch({ headless: true });
    let page = await browser.newPage();

    let frameNum = 0;
    // Expose a handler to the page
    await page.exposeFunction('onCustomEvent', async ({ type, detail }) => {
      // let interval = setInterval(async () => {
        frameNum++;
        await page.screenshot({path: `./image_screenshots/frame${String(frameNum).padStart(5, '0')}.png`});
      // }, 300 );
    });

    // listen for events of type 'status' and
    // pass 'type' and 'detail' attributes to our exposed function
    await page.evaluateOnNewDocument(() => {
      window.addEventListener('snap', ({ type, detail }) => {
        window.onCustomEvent({ type, detail });
      });
    });

    await page.goto('https://testing.local/districts-of-india/districts_of_india.php');

    // let frameNum = 0;

    // let interval = setInterval(async () => {
    //   frameNum++;
    //   await page.screenshot({path: `./image_screenshots/frame${String(frameNum).padStart(5, '0')}.png`});
    // }, 300 );

/*    await Page.loadEventFired();
    await Page.startScreencast({format: 'png', everyNthFrame: 1});

    let counter = 0;
    while(counter < 100){
      const {data, metadata, sessionId} = await Page.screencastFrame();
      console.log(metadata);
      await Page.screencastFrameAck({sessionId: sessionId});
      counter++;
    }*/

    // setTimeout(async () => {
    //   await clearInterval(interval);
    // }, 5000)
      // await page.close();
      // await browser.close();

    page.evaluate(() => {
      playAnimations();
    });
}

run();