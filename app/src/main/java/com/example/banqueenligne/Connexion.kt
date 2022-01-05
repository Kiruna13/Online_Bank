package com.example.banqueenligne

import android.os.Bundle
import android.util.Log
import android.view.View
import android.view.Window
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.*
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.snackbar.Snackbar
import org.json.JSONException
import org.json.JSONObject
import java.math.BigInteger
import java.security.MessageDigest

class Connexion : AppCompatActivity() {

    lateinit var identifiantInput : EditText
    lateinit var passwordInput : EditText
    lateinit var btnSend : Button
    lateinit var mQueue : RequestQueue

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.connexion)
        identifiantInput = findViewById(R.id.identifiant)
        passwordInput = findViewById(R.id.password)
        btnSend = findViewById(R.id.btn_send)
        mQueue = Volley.newRequestQueue(this)

        btnSend.setOnClickListener(View.OnClickListener {
            userConnection()
        })

    }

    fun userConnection() {
        val identifiant = identifiantInput?.text.toString()
        val password = passwordInput?.text.toString()
        val url = "http://192.168.0.36/onlineBankAPI/v1/?op=getPassword" //IP A CHANGER
        lateinit var data : JSONObject

        val request = object : StringRequest(Request.Method.POST, url,
            Response.Listener<String> { response ->
                data = JSONObject(response)
                var personnes = data.optJSONArray("personnes")
                var fetchedPassword : String = personnes!!.getJSONObject(0).optString("password")
                authentication(password, fetchedPassword)
            },
            Response.ErrorListener { error : VolleyError ->
                println(error)
            }) {
                @Throws(AuthFailureError::class)
                override fun getParams() : Map<String, String> {
                    val params = HashMap<String, String>()
                    params["identifiant"] = identifiant
                    return params
                }
            }

        mQueue.add(request)
    }

    fun authentication(password : String, fetchedPassword : String) {
        val cryptedPassword = toMd5(password)
        if (cryptedPassword.equals(fetchedPassword)) {
            println("AUTHENTIFICATION REUSSIE")
        }
    }

    fun toMd5(input:String): String {
        val md = MessageDigest.getInstance("MD5")
        return BigInteger(1, md.digest(input.toByteArray())).toString(16).padStart(32, '0')
    }

}