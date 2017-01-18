//
//  ViewController.swift
//  sharetoself
//
//  Created by mac13 on 20.10.16.
//  Copyright © 2016 mac13. All rights reserved.
//

import UIKit

class ViewController: UIViewController,  UITextFieldDelegate {
    @IBOutlet var scrollView: UIScrollView!
    @IBOutlet var emailView: UIView!
    @IBOutlet var facebookView: UIView!
    @IBOutlet var slackView: UIView!
    @IBOutlet var save: UIButton!
    @IBOutlet var emailSwitchView: UISwitch!
    @IBOutlet var facebookSwitchView: UISwitch!
    @IBOutlet var slackSwitchView: UISwitch!
    @IBOutlet var email: UITextField!
    @IBOutlet var prefix: UITextField!
    var emailStr: String!
    var prefixStr: String!

    var uuid: String?
    
    let limitLength = 20

    @IBAction func slackSwitch(_ sender: UISwitch) {
        if(sender.isOn){
            slackView.isHidden = false
        } else {
            slackView.isHidden = true
        }
    }
    @IBAction func facebookSwitch(_ sender: UISwitch) {
        if(sender.isOn){
            facebookView.isHidden = false
        } else {
            facebookView.isHidden = true
        }
    }
    @IBAction func emailSwitch(_ sender: UISwitch) {
        if(sender.isOn){
            emailView.isHidden = false
        } else {
            emailView.isHidden = true
        }
    }
    override func viewDidLoad() {
        super.viewDidLoad()
        prefix.delegate = self

        emailStr = UserDefaults.init(suiteName: "group.sharetoself")?.object(forKey: "email") as? String
        uuid = UserDefaults.init(suiteName: "group.sharetoself")?.object(forKey: "uuid") as? String
        if (uuid == nil || ((uuid != nil) && (uuid as String!).isEmpty)) {
            uuid = UIDevice.current.identifierForVendor!.uuidString
            UserDefaults.init(suiteName: "group.sharetoself")?.set(uuid, forKey: "uuid")
            UserDefaults.init(suiteName: "group.sharetoself")?.synchronize()
        }

        email.text = emailStr
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(ViewController.dismissKeyboard))

        view.addGestureRecognizer(tap)
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    @IBAction func save(_ sender: AnyObject) {
//        UserDefaults.init(suiteName: "group.voodoo-mobile.test")?.set(email.text, forKey: "email")
//        UserDefaults.init(suiteName: "group.voodoo-mobile.test")?.synchronize()
        UserDefaults.init(suiteName: "group.sharetoself")?.set(email.text, forKey: "email")
        UserDefaults.init(suiteName: "group.sharetoself")?.synchronize()
        request(email.text);
    }
    
    func request(_ email: String!) {
        
        var request = URLRequest(url: URL(string: "https://www.sharetoself.com/email/identify")!)
        request.httpMethod = "POST"
        let postString = "email=\(email as String)&id=\(uuid! as String)"
        request.httpBody = postString.data(using: .utf8)
        let task = URLSession.shared.dataTask(with: request) { data, response, error in
            guard let data = data, error == nil else {                                                 // check for fundamental networking error
                print("error=\(error)")
                return
            }
            
            if let httpStatus = response as? HTTPURLResponse, httpStatus.statusCode != 200 {           // check for http errors
                print("statusCode should be 200, but is \(httpStatus.statusCode)")
                print("response = \(response)")
            }
            
            let responseString = String(data: data, encoding: .utf8)
            print("responseString = \(responseString)")
        }
        task.resume()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        registerKeyboardNotifications()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        unregisterKeyboardNotifications()
    }
    
    func registerKeyboardNotifications() {
        NotificationCenter.default.addObserver(self,
                                               selector: #selector(ViewController.keyboardDidShow(notification:)),
                                               name: NSNotification.Name.UIKeyboardDidShow,
                                               object: nil)
        NotificationCenter.default.addObserver(self,
                                               selector: #selector(ViewController.keyboardWillHide(notification:)),
                                               name: NSNotification.Name.UIKeyboardWillHide,
                                               object: nil)
    }
    
    func unregisterKeyboardNotifications() {
        NotificationCenter.default.removeObserver(self)
    }
    
    
    func keyboardDidShow(notification: NSNotification) {
        let userInfo: NSDictionary = notification.userInfo! as NSDictionary
        let keyboardInfo = userInfo[UIKeyboardFrameBeginUserInfoKey] as! NSValue
        let keyboardSize = keyboardInfo.cgRectValue.size
        let contentInsets = UIEdgeInsets(top: 0, left: 0, bottom: keyboardSize.height, right: 0)
        scrollView.contentInset = contentInsets
        scrollView.scrollIndicatorInsets = contentInsets
    }
    
    func keyboardWillHide(notification: NSNotification) {
        scrollView.contentInset = UIEdgeInsets.zero
        scrollView.scrollIndicatorInsets = UIEdgeInsets.zero
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func textField(_ textField: UITextField, shouldChangeCharactersIn range: NSRange, replacementString string: String) -> Bool {
        guard let text = textField.text else { return true }
        let newLength = text.characters.count + string.characters.count - range.length
        return newLength <= limitLength
    }

}

